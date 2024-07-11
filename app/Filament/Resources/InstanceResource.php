<?php

namespace App\Filament\Resources;

use App\Enums\InstanceStatus;
use App\Filament\Resources\CategoryResource\RelationManagers\WebhooksRelationManager;
use App\Filament\Resources\InstanceResource\Pages;
use App\Filament\Resources\InstanceResource\RelationManagers\SitesRelationManager;
use App\Models\Instance;
use App\Models\Site;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class InstanceResource extends Resource
{
    protected static ?string $model = Instance::class;

    protected static ?string $slug = 'instances';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?Instance $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?Instance $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('name')
                    ->required(),

                Select::make('status')
                    ->options(InstanceStatus::class)
                    ->required(),

                TextInput::make('instance_id')
                    ->required(),

                KeyValue::make('payload')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('instance_id')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('sites_count')
                    ->sortable()
                    ->counts('sites'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->default(InstanceStatus::ACTIVE->value)
                    ->options(InstanceStatus::class),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('bulk_assign_site')
                    ->form([
                        Select::make('sites')
                            ->multiple()
                            ->options(fn () => Site::pluck('name', 'id')),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $records->each(function (Instance $record) use ($data) {
                            $record->sites()->syncWithoutDetaching($data['sites']);
                        });
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstances::route('/'),
            'view' => Pages\ViewInstance::route('/{record}/view'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getRelations(): array
    {
        return [
            SitesRelationManager::class,
            WebhooksRelationManager::class,
        ];
    }
}
