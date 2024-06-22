<?php

namespace App\Modules\Webhook;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class WebhookSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        if (! config('services.green-api.secret')) {
            return false;
        }

        if (! $request->bearerToken()) {
            return false;
        }

        if ($request->bearerToken() !== config('services.green-api.secret')) {
            return false;
        }

        return true;
    }
}
