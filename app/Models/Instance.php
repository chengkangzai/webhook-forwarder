<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    protected $fillable = [
        'instance_token',
        'instance_id',
        'name',
        'status',
    ];
}
