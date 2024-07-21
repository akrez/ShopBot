<?php

namespace App\Enums\Contact;

use App\Enums\Enum;

enum ContactType: string
{
    use Enum;

    case ADDRESS = 'address';
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case PHONE = 'phone';
    case EMAIL = 'email';
    case INSTAGRAM = 'instagram';
}
