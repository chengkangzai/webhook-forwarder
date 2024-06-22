<?php

namespace App\Integrations\GreenApi\Requests\Partner;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetInstances extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $partnerToken,
    ) {}

    public function resolveEndpoint(): string
    {

        return '/partner/getInstances/'.$this->partnerToken;
    }
}
