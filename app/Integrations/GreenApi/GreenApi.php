<?php

namespace App\Integrations\GreenApi;

use App\Integrations\GreenApi\Resource\Account;
use App\Integrations\GreenApi\Resource\Partner;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

class GreenApi extends Connector
{
    use HasTimeout;

    protected int $connectTimeout = 30;

    protected int $requestTimeout = 60;

    public ?int $tries = 3;

    public ?int $retryInterval = 500;

    public ?bool $useExponentialBackoff = true;

    public function __construct(
        private readonly string $instanceId,
        private readonly string $instanceToken,
        public ?string $baseUrl = 'https://api.greenapi.com',
        public ?string $mediaBaseUrl = 'https://media.greenapi.com',
    ) {
        if (empty($this->baseUrl)) {
            $this->baseUrl = 'https://api.greenapi.com';
        }

        if (empty($this->mediaBaseUrl)) {
            $this->mediaBaseUrl = 'https://media.greenapi.com';
        }
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function account(): Account
    {
        return new Account($this, $this->instanceId, $this->instanceToken);
    }

    public function partner(): Partner
    {
        return new Partner($this);
    }
}
