<?php

namespace App\Supports\MessageProcessors;

use App\Supports\DefaultMessageProcessor;

class RequestContactMessageProcessor extends DefaultMessageProcessor
{
    public function isProcessor()
    {
        if (
            ! empty($content['contact']['user_id'])
            and ! empty($content['contact']['first_name'])
            and ! empty($content['contact']['phone_number'])
        ) {
            return true;
        }

        return false;
    }
}
