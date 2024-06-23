<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForwardWebhookCallJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Webhook $webhookCall)
    {
    }

    public function handle(): void
    {
        info('RUNING');
        $this->webhookCall->instance()->first()->sites()->each(function (Site $site) {
        info('url : '.$site->url);
            \Spatie\WebhookServer\WebhookCall::create()
                ->url($site->url)
                ->payload($this->webhookCall->payload)
                ->useSecret(config('services.green-api.secret'))
                ->dispatch();
        });
    }
}
