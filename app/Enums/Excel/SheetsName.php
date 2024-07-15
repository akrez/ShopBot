<?php

namespace App\Enums\Excel;

use App\Enums\Enum;

enum SheetsName: string
{
    use Enum;

    case PRODUCTS = 'products';
}
