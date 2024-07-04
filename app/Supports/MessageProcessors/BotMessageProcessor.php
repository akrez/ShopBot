<?php

namespace App\Supports\MessageProcessors;

use App\Supports\DefaultMessageProcessor;

class BotMessageProcessor extends DefaultMessageProcessor
{
    public function isProcessor()
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
