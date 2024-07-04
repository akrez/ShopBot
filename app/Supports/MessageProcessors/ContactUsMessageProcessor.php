<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Supports\DefaultMessageProcessor;

class ContactUsMessageProcessor extends DefaultMessageProcessor
{
    public function isProcessor()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CONTACT_US->value) {
            return true;
        }

        return false;
    }
}
