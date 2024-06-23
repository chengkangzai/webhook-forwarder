<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use App\Models\Site;
use App\Models\Webhook;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Spatie\WebhookServer\WebhookCall;

class ViewWebhook extends ViewRecord
{
    protected static string $resource = WebhookResource::class;

    /** @var Model|int|string|null|Webhook $record */
    public Model|int|string|null $record;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('forward')
                ->form([
                    Select::make('site')
                        ->options(function () {
                            return $this->record->instance()->first()->sites()->pluck('name', 'sites.id');
                        })
                ])
                ->action(function (Webhook $webhook, array $data) {
                    $site = Site::find($data['site']);

                    WebhookCall::create()
                        ->doNotVerifySsl()
                        ->url($site->url)
                        ->payload($webhook->payload)
                        ->withHeaders([
                            'authorization' => $webhook->headers['authorization']
                        ])
                        ->doNotSign()
                        ->dispatchSync();

                    Notification::make('success')
                        ->success()
                        ->title('Success')
                        ->body('Successfully forwarded to ' . $site->url)
                        ->send();
                })
        ];
    }
}
