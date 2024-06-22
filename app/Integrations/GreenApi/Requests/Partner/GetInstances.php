<?php

namespace App\Integrations\GreenApi\Requests\Partner;

use App\Integrations\GreenApi\Data\GreenApiInstanceData;
use Carbon\Carbon;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

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

    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->collect()
            ->map(function (array $item) {
                return new GreenApiInstanceData(
                    data_get($item, 'idInstance'),
                    data_get($item, 'name'),
                    data_get($item, 'typeInstance'),
                    data_get($item, 'typeAccount'),
                    data_get($item, 'partnerUserUiid'),
                    Carbon::parse(data_get($item, 'timeCreated')),
                    Carbon::parse(data_get($item, 'timeDeleted')),
                    data_get($item, 'apiTokenInstance'),
                    data_get($item, 'deleted'),
                    data_get($item, 'tariff'),
                    data_get($item, 'isFree'),
                    data_get($item, 'isPartner'),
                    Carbon::parse(data_get($item, 'expirationDate')),
                    data_get($item, 'isExpired'),
                );
            });

    }
}
