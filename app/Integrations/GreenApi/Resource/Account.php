<?php

namespace App\Integrations\GreenApi\Resource;

use App\Integrations\GreenApi\Requests\Account\GetAccountSettings;
use App\Integrations\GreenApi\Requests\Account\GetAccountState;
use App\Integrations\GreenApi\Requests\Account\LogoutAccount;
use App\Integrations\GreenApi\Requests\Account\SetAccountSettings;
use App\Integrations\GreenApi\Resource;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

class Account extends Resource
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getAccountSettings(): Response
    {
        return $this->connector->send(new GetAccountSettings($this->instanceId, $this->instanceToken));
    }

    /**
     * @param  ?string  $markIncomingMessagesReaded  Mark incoming messages as read or not, possible variants: yes,no. Ignored if markIncomingMessagesReadedOnReply is 'yes'.
     * @param  ?string  $markIncomingMessagesReadedOnReply  Mark incoming messages as read when posting a message to the chat via API, possible variants: yes,no. If it is 'yes', then the markIncomingMessagesReaded setting is ignored.
     * @param  ?string  $outgoingWebhook  Get notifications about outgoing messages sending/delivering/reading statuses, possible variants: yes,no. noAccount and failed statuses cannot be disabled, it is necessary to implement the processing of this notification.
     * @param  ?string  $outgoingMessageWebhook  Get notifications about messages sent from the phone, possible variants: yes,no
     * @param  ?string  $outgoingApimessageWebhook  Get notifications about messages sent from API, possible variants: yes,no. When sending a message to a non-existing WhatsApp account, the notification will not come.
     * @param  ?string  $incomingWebhook  Get notifications about incoming messages, possible variants: yes,no
     * @param  ?string  $stateWebhook  Get notifications about the account authorization state change, possible variants: yes,no
     * @param  ?string  $keepOnlineStatus  Keep the account online, possible variants: yes,no
     */
    public function setAccountSettings(
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
        $setAccountSettings = new SetAccountSettings(
            $this->instanceId,
            $this->instanceToken,
            ...func_get_args()
        );

        return $this->connector->send($setAccountSettings);
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getAccountState(): Response
    {
        return $this->connector->send(new GetAccountState($this->instanceId, $this->instanceToken));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function logoutAccount(): Response
    {
        return $this->connector->send(new LogoutAccount($this->instanceId, $this->instanceToken));
    }
}
