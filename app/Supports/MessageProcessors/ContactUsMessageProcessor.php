<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Enums\MessageProcessor\ResponseTypeEnum;
use App\Supports\DefaultMessageProcessor;

class ContactUsMessageProcessor extends DefaultMessageProcessor
{
    public function getResponseType()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CONTACT_US->value) {
            return ResponseTypeEnum::CONTACT_US->value;
        }
    }
}
