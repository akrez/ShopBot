<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ResponseTypeEnum;
use App\Supports\DefaultMessageProcessor;

class BotMessageProcessor extends DefaultMessageProcessor
{
    public function getResponseType()
    {
        if (
            isset($content['from']['is_bot'])
            and $content['from']['is_bot']
        ) {
            return ResponseTypeEnum::BOT->value;
        }
    }
}
