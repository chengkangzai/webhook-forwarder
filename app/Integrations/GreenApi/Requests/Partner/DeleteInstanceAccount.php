<?php

namespace App\Integrations\GreenApi\Requests\Partner;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteInstanceAccount extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $partnerToken,
        private readonly string $instanceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/partner/deleteInstanceAccount/'.$this->partnerToken;
    }

    public function defaultBody(): array
    {
        return [
            'idInstance' => $this->instanceId,
        ];
    }
}
