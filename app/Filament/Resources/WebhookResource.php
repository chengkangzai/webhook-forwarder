<?php

namespace App\Filament\Resources;

use App\Enums\InstanceStatus;
use App\Enums\WebhookStatus;
use App\Filament\Resources\WebhookResource\Pages;
use App\Models\Site;
use App\Models\Webhook;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\WebhookServer\WebhookCall;
use ValentinMorice\FilamentJsonColumn\FilamentJsonColumn;

class WebhookResource extends Resource
{
    protected static ?string $model = Webhook::class;

    protected static ?string $slug = 'webhook-calls';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('instance_id')
                    ->columnSpanFull()
                    ->relationship('instance', 'name')
                    ->required(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('url')
                    ->required()
                    ->url(),

                Select::make('status')
                    ->options(WebhookStatus::class),

                TextInput::make('forwarded_at'),

                FilamentJsonColumn::make('headers')
                    ->viewerOnly(),
                FilamentJsonColumn::make('payload')
                    ->viewerOnly(),
                KeyValue::make('exception')
                    ->columnSpanFull(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?Webhook $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?Webhook $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll()
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('forwarded_at')
                    ->dateTime(),

                TextColumn::make('url')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                TextColumn::make('webhookType')
                    ->badge()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                TextColumn::make('instance.name')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\ListWebhooks),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->columnSpanFull(),
            ])
            ->filtersFormColumns(2)
            ->filters([
                SelectFilter::make('instance_id')
                    ->columnSpanFull()
                    ->label('Instance')
                    ->multiple()
                    ->relationship('instance', 'name', function (Builder $query) {
                        $query->where('status', InstanceStatus::ACTIVE)
                            ->whereHas('webhooks');
                    })
                    ->preload()
                    ->searchable(),

                SelectFilter::make('status')
                    ->columnSpanFull()
                    ->options(WebhookStatus::class),

                Filter::make('created_at')
                    ->form([
                        TextInput::make('older_than_days'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['older_than_days'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', now()->subDays($data['older_than_days'])),
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        Webhook::whereIn('id', $records->pluck('id'))->delete();

                        return Notification::make('success')
                            ->success()
                            ->title('Webhook deleted')
                            ->body('Webhooks have been deleted successfully.')
                            ->send();
                    }),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('forward')
                    ->icon('heroicon-o-arrow-up-right')
                    ->visible(fn (Webhook $record) => $record->instance_id !== null)
                    ->form([
                        Select::make('site')
                            ->multiple()
                            ->options(fn (Webhook $record) => $record->instance()->first()->activeSites()->pluck('name', 'sites.id')),
                    ])
                    ->action(function (Webhook $webhook, array $data) {
                        $sites = Site::find($data['site']);

                        foreach ($sites as $site) {
                            WebhookCall::create()
                                ->doNotVerifySsl()
                                ->url($site->url)
                                ->payload($webhook->payload)
                                ->withHeaders([
                                    'authorization' => $webhook->headers['authorization'],
                                ])
                                ->doNotSign()
                                ->dispatchSync();

                            Notification::make('success'.$site->id)
                                ->success()
                                ->title('Success')
                                ->body('Successfully forwarded to '.$site->url)
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhooks::route('/'),
            'view' => Pages\ViewWebhook::route('/{record}/view'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
