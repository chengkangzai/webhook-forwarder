<?php

namespace App\Integrations\GreenApi\Requests\Sending;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendText extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $instanceId,
        protected readonly ?string $instanceToken = null,
        protected readonly ?string $chatId = null,
        protected readonly ?string $message = null,
        protected readonly ?string $quotedMessageId = null,
    ) {
        if (! str($this->chatId)->contains('@')) {
            throw new \Exception('Invalid chatId');
        }
    }

    public function resolveEndpoint(): string
    {
        return "/waInstance$this->instanceId/sendMessage/$this->instanceToken";
    }

    public function defaultBody(): array
    {
        return [
            'chatId' => $this->chatId,
            'message' => $this->message,
            'quotedMessageId' => $this->quotedMessageId ?? '',
        ];
    }
}
