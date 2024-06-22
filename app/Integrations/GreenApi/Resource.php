<?php

namespace App\Integrations\GreenApi;

use Saloon\Http\Connector;

class Resource
{
    public function __construct(
        protected readonly Connector $connector,
        protected readonly ?string $instanceId,
        protected readonly ?string $instanceToken,
    ) {}
}
