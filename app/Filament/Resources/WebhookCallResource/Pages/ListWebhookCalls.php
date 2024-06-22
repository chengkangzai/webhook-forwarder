<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;

use App\Filament\Resources\WebhookCallResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebhookCalls extends ListRecords
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
