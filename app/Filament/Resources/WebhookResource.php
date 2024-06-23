<?php

namespace App\Filament\Resources;

use App\Enums\InstanceStatus;
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
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\WebhookServer\WebhookCall;

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

                KeyValue::make('headers')
                    ->columnSpanFull(),
                KeyValue::make('payload')
                    ->columnSpanFull(),
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
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url'),
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
                    ->label('instance')
                    ->multiple()
                    ->relationship('instance', 'name', function (Builder $query) {
                        $query->where('status', InstanceStatus::ACTIVE);
                    })
                    ->preload()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
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
