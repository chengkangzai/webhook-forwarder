<?php

namespace App\Jobs;

use App\Enums\WebhookStatus;
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
        $instance = Instance::where('instance_id', data_get($this->payload, 'instanceData.idInstance'))->first();

        if ($instance === null) {
            Webhook::create([
                'name' => 'default',
                'url' => $this->url,
                'headers' => $this->headers,
                'payload' => $this->payload,
                'status' => WebhookStatus::PENDING,
            ]);

            return;
        }

        $webhook = Webhook::create([
            'name' => 'default',
            'url' => $this->url,
            'headers' => $this->headers,
            'payload' => $this->payload,
            'instance_id' => $instance->id,
            'status' => WebhookStatus::PENDING,
        ]);

        dispatch(new ForwardWebhookCallJob($webhook));
    }
}
