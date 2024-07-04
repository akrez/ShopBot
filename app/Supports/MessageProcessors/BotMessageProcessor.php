<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessorResponseTypeEnum;
use App\Supports\DefaultMessageProcessor;

class BotMessageProcessor extends DefaultMessageProcessor
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
