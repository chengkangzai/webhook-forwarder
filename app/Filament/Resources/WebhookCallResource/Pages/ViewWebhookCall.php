<?php

namespace App\Filament\Resources\WebhookCallResource\Pages;

use App\Filament\Resources\WebhookCallResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

class ViewWebhookCall extends ViewRecord
{
    protected static string $resource = WebhookCallResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
