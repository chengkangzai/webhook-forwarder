<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookCallResource\Pages;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\WebhookClient\Models\WebhookCall;

class WebhookCallResource extends Resource
{
    protected static ?string $model = WebhookCall::class;

    protected static ?string $slug = 'webhook-calls';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('url')
                    ->required()
                    ->url(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?WebhookCall $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?WebhookCall $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ID'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url'),

                TextColumn::make('headers'),

                TextColumn::make('payload'),

                TextColumn::make('exception'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->columnSpanFull(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('name'),
                TextEntry::make('url'),
                KeyValueEntry::make('headers')
                    ->columnSpanFull(),
                KeyValueEntry::make('payload')
                    ->columnSpanFull(),
                KeyValueEntry::make('exception')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Created Date'),
                TextEntry::make('updated_at')
                    ->label('Last Modified Date'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookCalls::route('/'),
            'view' => Pages\ViewWebhookCall::route('/{record}/view'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
