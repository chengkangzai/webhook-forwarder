<?php

namespace App\Modules\Webhook;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class WebhookSignatureValidator implements SignatureValidator
{

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return true;
    }
}
