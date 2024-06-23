<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InstanceSite extends Pivot
{
    protected $fillable = [
        'instance_id',
        'site_id',
    ];
}
