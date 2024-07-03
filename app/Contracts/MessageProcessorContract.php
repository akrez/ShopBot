<?php

namespace App\Contracts;

use App\Models\Message;

interface MessageProcessorContract
{
    public function __construct(Message $message);

    public function getResponseType();

    public function sendResponse();
}
