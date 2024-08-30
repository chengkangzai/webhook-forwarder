<?php

namespace App\Jobs;

use App\Enums\WebhookStatus;
use App\Integrations\GreenApi\GreenApi;
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

        $webhook = Webhook::create([
            'name' => 'default',
            'url' => $this->url,
            'headers' => $this->headers,
            'payload' => $this->payload,
            'instance_id' => $instance?->id,
            'status' => WebhookStatus::PENDING,
        ]);

        if (data_get($this->payload, 'stateInstance') === 'blocked' && $instance) {
            $credential['instance_id'] = config('');
            $greenApi = new GreenApi($credential['instance_id'], $credential['instance_token']);
            $greenApi->sending()->sendText('60127067086@c.us', $instance->name.' has been blocked');
            $greenApi->sending()->sendText('120363042523703808@g.us', $instance->name.' has been blocked');
        }

        dispatch(new ForwardWebhookCallJob($webhook));
    }
}
