<?php

namespace App\Supports\MessageProcessor;

use App\Enums\MessageProcessorResponseTypeEnum;
use App\Supports\MessageProcessor;

class BotMessageProcessor extends MessageProcessor
{
    public function getResponseType()
    {
        if (
            isset($content['from']['is_bot'])
            and $content['from']['is_bot']
        ) {
            return MessageProcessorResponseTypeEnum::BOT->value;
        }
    }
}
