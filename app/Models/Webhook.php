<?php

namespace App\Models;

use App\Enums\WebhookStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Webhook extends Model
{
    use Prunable;

    protected $fillable = [
        'name',
        'url',
        'headers',
        'payload',
        'exception',
        'instance_id',
        'status',
        'forwarded_at',
    ];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'payload' => 'array',
            'exception' => 'array',
            'status' => WebhookStatus::class,
        ];
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public function webhookType(): Attribute
    {
        return Attribute::make(
            get: fn () => data_get($this->payload, 'typeWebhook', 'N/A')
        );
    }

    public function prunable(): Builder
    {
        return static::query()
            //Prune data that is 60 days old
            ->where('created_at', '<=', now()->subDays(14));
    }
}
