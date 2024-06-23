<?php

namespace App\Jobs;

use App\Models\Instance;
use App\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreWebhookCallJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $payload,
        public string $url,
        public array $headers,
    ) {}

    public function handle(): void
    {
        $instance = Instance::where('instance_id', 1101823699)->first();

        if ($instance === null) {
            Webhook::create([
                'name' => 'default',
                'url' => $this->url,
                'headers' => $this->headers,
                'payload' => $this->payload,
            ]);

            return;
        }

        $webhook = Webhook::create([
            'name' => 'default',
            'url' => $this->url,
            'headers' => $this->headers,
            'payload' => $this->payload,
            'instance_id' => $instance->id,
        ]);

        dispatch(new ForwardWebhookCallJob($webhook));
    }
}
