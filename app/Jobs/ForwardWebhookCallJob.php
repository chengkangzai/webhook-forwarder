<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookServer\WebhookCall;

class ForwardWebhookCallJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Webhook $webhook) {}

    public function handle(): void
    {
        $this->webhook->instance()->first()->sites()->each(function (Site $site) {
            WebhookCall::create()
                ->doNotVerifySsl()
                ->url($site->url)
                ->payload($this->webhook->payload)
                ->withHeaders([
                    'authorization' => $this->webhook->headers['authorization'],
                ])
                ->doNotSign()
                ->dispatchSync();
        });
    }
}
