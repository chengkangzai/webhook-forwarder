<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instance extends Model
{
    protected $fillable = [
        'instance_token',
        'instance_id',
        'name',
        'status',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'status' => Instancestatus::class,
        ];
    }

    public function webhookCalls(): HasMany
    {
        return $this->hasMany(WebhookCall::class);
    }
}
