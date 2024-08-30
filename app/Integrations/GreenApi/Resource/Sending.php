<?php

namespace App\Integrations\GreenApi\Resource;

use App\Integrations\GreenApi\Requests\Sending\SendText;
use App\Integrations\GreenApi\Resource;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

class Sending extends Resource
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function sendText(string $chatId, string $message, ?string $quotedMessageId = null): Response
    {
        return $this->connector->send(new SendText($this->instanceId, $this->instanceToken, $chatId, $message, $quotedMessageId));
    }
}
