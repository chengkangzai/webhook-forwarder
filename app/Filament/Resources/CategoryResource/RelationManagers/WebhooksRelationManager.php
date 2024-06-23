<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\WebhookResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class WebhooksRelationManager extends RelationManager
{
    protected static string $relationship = 'webhooks';

    public function form(Form $form): Form
    {
        return WebhookResource::form($form);
    }

    public function table(Table $table): Table
    {
        return WebhookResource::table($table);
    }
}
