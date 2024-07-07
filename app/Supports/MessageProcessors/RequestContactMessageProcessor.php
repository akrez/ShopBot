<?php

namespace App\Supports\MessageProcessors;

class RequestContactMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
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
