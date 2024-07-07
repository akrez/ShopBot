<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;

class ContactUsMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CONTACT_US->value) {
            return true;
        }

        return false;
    }
}
