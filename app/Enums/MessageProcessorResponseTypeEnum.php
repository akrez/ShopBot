<?php

namespace App\Enums;

enum MessageProcessorResponseTypeEnum: string
{
    use Enum;

    case DEFAULT = 'default';
    case BOT = 'bot';
}
