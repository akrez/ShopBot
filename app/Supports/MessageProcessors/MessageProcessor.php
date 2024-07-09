<?php

namespace App\Supports\MessageProcessors;

use App\Contracts\MessageProcessorContract;
use App\Models\Bot;
use App\Models\Message;

class MessageProcessor implements MessageProcessorContract
{
    public function __construct(public Bot $bot, public Message $message) {}

    public function shouldProcess()
    {
        return true;
    }

    public function process() {}
}
