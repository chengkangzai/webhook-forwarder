<?php

namespace App\Integrations\GreenApi\Resource;

use App\Integrations\GreenApi\GreenApi;
use App\Integrations\GreenApi\Requests\Partner\CreateInstance;
use App\Integrations\GreenApi\Requests\Partner\DeleteInstanceAccount;
use App\Integrations\GreenApi\Requests\Partner\GetInstances;
use Saloon\Http\Response;

class Partner
{
    public function __construct(
        private readonly GreenApi $connector
    ) {}

    public function createInstance(
        ?string $webhookUrl = null,
        ?string $webhookUrlToken = null,
        ?int $delaySendMessagesMilliseconds = null,
        ?string $markIncomingMessagesReaded = null,
        ?string $markIncomingMessagesReadedOnReply = null,
        ?string $outgoingWebhook = null,
        ?string $outgoingMessageWebhook = null,
        ?string $outgoingApimessageWebhook = null,
        ?string $incomingWebhook = null,
        ?string $stateWebhook = null,
        ?string $keepOnlineStatus = null,
    ): Response {
        $setAccountSettings = new CreateInstance(
            config('services.green-api.partner_token'),
            ...func_get_args()
        );

        return $this->connector->send($setAccountSettings);
    }

    public function deleteInstanceAccount(string $instanceId): Response
    {
        $deleteInstanceAccount = new DeleteInstanceAccount(
            config('services.green-api.partner_token'),
            $instanceId
        );

        return $this->connector->send($deleteInstanceAccount);
    }

    public function getInstances()
    {
        $getInstances = new GetInstances(
            config('services.green-api.partner_token')
        );

        return $this->connector->send($getInstances);
    }
}
