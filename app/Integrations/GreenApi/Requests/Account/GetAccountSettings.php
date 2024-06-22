<?php

namespace App\Integrations\GreenApi\Requests\Account;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get account settings
 */
class GetAccountSettings extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/waInstance$this->instanceId/getSettings/$this->instanceToken";
    }

    public function __construct(
        private readonly string $instanceId,
        private readonly string $instanceToken,
    ) {}
}
