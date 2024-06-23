<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookResource\Pages;
use App\Models\Webhook;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->columnSpanFull(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([

            ])
            ->bulkActions([
                //
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
