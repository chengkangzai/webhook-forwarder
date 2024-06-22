<?php

namespace App\Jobs;

use App\Models\Instance;
use App\Models\WebhookCall;
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
        public array  $payload,
        public string $url,
        public array  $headers,
    )
    {
    }

    public function handle(): void
    {
        Instance::where('instance_id', $this->payload['instanceData']['idInstance'])
            ->first()
            ->webhookCalls()
            ->create([
                'name' => 'default',
                'url' => $this->url,
                'headers' => $this->headers,
                'payload' => $this->payload,
            ]);
    }
}
