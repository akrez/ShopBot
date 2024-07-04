<?php

namespace App\Contracts;

use App\Models\Bot;
use App\Models\Message;

interface MessageProcessorContract
{
    public function __construct(Bot $bot, Message $message);

    public function isProcessor();

    public function sendResponse();
}
