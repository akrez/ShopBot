<?php

namespace App\Supports\MessageProcessor;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessorResponseTypeEnum;
use App\Models\Message;

class BotMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Message $message) {}

    public function getResponseType()
    {
        if (
            isset($content['from']['is_bot'])
            and $content['from']['is_bot']
        ) {
            return MessageProcessorResponseTypeEnum::BOT->value;
        }
    }

    public function sendResponse() {}
}
