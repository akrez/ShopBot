<?php

namespace App\Enums\Product;

use App\Enums\Enum;

enum ProductStatus: string
{
    use Enum;

    case DEACTIVE = 'deactive';
    case ACTIVE = 'active';
}
