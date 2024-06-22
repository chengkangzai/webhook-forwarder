<?php

namespace App\Integrations\GreenApi\Data;

use Carbon\Carbon;

class GreenApiInstanceData
{
    public function __construct(
        public string $idInstance,
        public string $name,
        public string $typeInstance,
        public string $typeAccount,
        public string $partnerUserUiid,
        public Carbon $timeCreated,
        public Carbon $timeDeleted,
        public string $apiTokenInstance,
        public bool $deleted,
        public string $tariff,
        public bool $isFree,
        public bool $isPartner,
        public Carbon $expirationDate,
        public bool $isExpired,
    ) {}

    public function toArray(): array
    {
        return [
            'idInstance' => $this->idInstance,
            'name' => $this->name,
            'typeInstance' => $this->typeInstance,
            'typeAccount' => $this->typeAccount,
            'partnerUserUiid' => $this->partnerUserUiid,
            'timeCreated' => $this->timeCreated->toString(),
            'timeDeleted' => $this->timeDeleted->toString(),
            'apiTokenInstance' => $this->apiTokenInstance,
            'deleted' => $this->deleted,
            'tariff' => $this->tariff,
            'isFree' => $this->isFree,
            'isPartner' => $this->isPartner,
            'expirationDate' => $this->expirationDate->toString(),
            'isExpired' => $this->isExpired,
        ];
    }
}
