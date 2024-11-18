<?php

namespace App\Filament\Resources\InstanceResource\Pages;

use App\Filament\Resources\InstanceResource;
use App\Integrations\GreenApi\GreenApi;
use App\Models\Instance;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewInstance extends ViewRecord
{
    protected static string $resource = InstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('link_to_forwarder')
                ->action(function (Instance $instance) {
                    $greenApi = new GreenApi($instance->instance_id, $instance->instance_token);

                    $greenApi->account()->setAccountSettings(
                        webhookUrl: route('webhook'),
                        webhookUrlToken: config('services.green-api.secret'),
                        outgoingWebhook: 'yes',
                        outgoingApimessageWebhook: 'yes',
                    );

                    Notification::make('success')
                        ->success()
                        ->title('Success!')
                        ->body('Green API setting has been updated.')
                        ->send();
                })
        ];
    }
}
