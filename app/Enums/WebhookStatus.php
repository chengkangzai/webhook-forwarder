<?php

namespace App\Enums;

enum WebhookStatus: string
{
    case PENDING = 'PENDING';
    case FORWARDED = 'FORWARDED';
    case FAILED = 'FAILED';
}
