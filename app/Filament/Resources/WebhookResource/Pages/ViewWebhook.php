<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use App\Models\Site;
use App\Models\Webhook;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Spatie\WebhookServer\WebhookCall;

class ViewWebhook extends ViewRecord
{
    protected static string $resource = WebhookResource::class;

    public static function getForwardAction(): Action
    {
        return Action::make('forward')
            ->form([
                Select::make('site')
                    ->options(fn (Webhook $record) => $record->instance()->first()->activeSites()->pluck('name', 'sites.id')),
            ])
            ->action(function (Webhook $webhook, array $data) {
                $site = Site::find($data['site']);

                WebhookCall::create()
                    ->doNotVerifySsl()
                    ->url($site->url)
                    ->payload($webhook->payload)
                    ->withHeaders([
                        'authorization' => $webhook->headers['authorization'],
                    ])
                    ->doNotSign()
                    ->dispatchSync();

                Notification::make('success')
                    ->success()
                    ->title('Success')
                    ->body('Successfully forwarded to '.$site->url)
                    ->send();
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            self::getForwardAction(),
        ];
    }
}
