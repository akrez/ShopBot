<?php

namespace App\Enums\Product;

use App\Enums\Enum;

enum ProductStatus: string
{
    use Enum;

    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
}
