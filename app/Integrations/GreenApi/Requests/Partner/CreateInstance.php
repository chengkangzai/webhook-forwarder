<?php

namespace App\Integrations\GreenApi\Requests\Partner;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CreateInstance extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $partnerToken,
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
        protected ?string $deviceWebhook = null,
        protected ?string $pollMessageWebhook = null,
        protected ?string $incomingBlockWebhook = null,
        protected ?string $incomingCallWebhook = null,

    ) {}

    public function resolveEndpoint(): string
    {
        return '/partner/createInstance/'.$this->partnerToken;
    }

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
            'deviceWebhook' => $this->deviceWebhook,
            'pollMessageWebhook' => $this->pollMessageWebhook,
            'incomingBlockWebhook' => $this->incomingBlockWebhook,
            'incomingCallWebhook' => $this->incomingCallWebhook,
        ];
    }
}
