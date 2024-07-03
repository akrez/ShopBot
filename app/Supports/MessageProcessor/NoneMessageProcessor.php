<?php

namespace App\Supports\MessageProcessor;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessorResponseTypeEnum;
use App\Models\Message;

class NoneMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Message $message) {}

    public function getResponseType()
    {
        return MessageProcessorResponseTypeEnum::NONE->value;
    }

    public function sendResponse() {}
}
