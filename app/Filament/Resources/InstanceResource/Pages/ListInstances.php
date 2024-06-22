<?php

namespace App\Filament\Resources\InstanceResource\Pages;

use App\Filament\Resources\InstanceResource;
use App\Services\ImportGreenApiInstance;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListInstances extends ListRecords
{
    protected static string $resource = InstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->action(function () {
                    app(ImportGreenApiInstance::class)->execute();

                    Notification::make('success')
                        ->success()
                        ->title('Import/Update Successfully')
                        ->send();
                }),
        ];
    }
}
