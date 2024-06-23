<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }

    public function activeSites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)->where('is_active', true);
    }
}
