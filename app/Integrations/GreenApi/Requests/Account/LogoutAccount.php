<?php

namespace App\Integrations\GreenApi\Requests\Account;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Logout account
 */
class LogoutAccount extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $instanceId,
        private readonly string $instanceToken,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/waInstance$this->instanceId/logout/$this->instanceToken";
    }
}
