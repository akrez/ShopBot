<?php

namespace App\Support\MessageProcessors;

use App\Support\MessageProcessor;

class FilterMessageProcessor extends MessageProcessor
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
