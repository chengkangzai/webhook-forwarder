<?php

namespace App\Integrations\GreenApi\Requests\Account;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Set account settings
 */
class SetAccountSettings extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return "/waInstance$this->instanceId/setSettings/$this->instanceToken";
    }

    public function __construct(
        private readonly string $instanceId,
        private readonly string $instanceToken,
        protected ?string $webhookUrl = null,
        protected ?string $webhookUrlToken = null,
        protected ?int $delaySendMessagesMilliseconds = null,
        protected ?string $markIncomingMessagesReaded = null,
        protected ?string $markIncomingMessagesReadedOnReply = null,
        protected ?string $outgoingWebhook = null,
        protected ?string $outgoingMessageWebhook = null,
        protected ?string $outgoingApimessageWebhook = null,
        protected ?string $incomingWebhook = null,
        protected ?string $stateWebhook = null,
        protected ?string $keepOnlineStatus = null,
    ) {}

    public function defaultBody(): array
    {
        return [
            'webhookUrl' => $this->webhookUrl,
            'webhookUrlToken' => $this->webhookUrlToken,
            'delaySendMessagesMilliseconds' => $this->delaySendMessagesMilliseconds,
            'markIncomingMessagesReaded' => $this->markIncomingMessagesReaded,
            'markIncomingMessagesReadedOnReply' => $this->markIncomingMessagesReadedOnReply,
            'outgoingWebhook' => $this->outgoingWebhook,
            'outgoingMessageWebhook' => $this->outgoingMessageWebhook,
            'outgoingAPIMessageWebhook' => $this->outgoingApimessageWebhook,
            'incomingWebhook' => $this->incomingWebhook,
            'stateWebhook' => $this->stateWebhook,
            'keepOnlineStatus' => $this->keepOnlineStatus,
        ];
    }
}
