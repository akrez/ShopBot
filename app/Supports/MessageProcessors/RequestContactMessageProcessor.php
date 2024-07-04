<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ResponseTypeEnum;
use App\Supports\DefaultMessageProcessor;

class RequestContactMessageProcessor extends DefaultMessageProcessor
{
    public function getResponseType()
    {
        if (
            ! empty($content['contact']['user_id'])
            and ! empty($content['contact']['first_name'])
            and ! empty($content['contact']['phone_number'])
        ) {
            return ResponseTypeEnum::REQUEST_CONTACT->value;
        }
    }
}
