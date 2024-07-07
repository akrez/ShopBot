<?php

namespace App\Supports\MessageProcessors;

class BotMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        if (
            isset($content['from']['is_bot'])
            and $content['from']['is_bot']
        ) {
            return true;
        }

        return false;
    }
}
