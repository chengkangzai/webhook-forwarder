<?php

namespace App\Models;

use App\Enums\InstanceStatus;
use Illuminate\Database\Eloquent\Model;

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
}
