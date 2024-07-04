<?php

namespace App\Supports\MessageProcessor;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessorResponseTypeEnum;
use App\Models\Message;

class DefaultMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Message $message) {}

    public function getResponseType()
    {
        return MessageProcessorResponseTypeEnum::DEFAULT->value;
    }

    public function sendResponse() {}
}
