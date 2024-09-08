<?php

namespace App\Enums\Package;

use App\Enums\Enum;

enum PackageStatus: string
{
    use Enum;

    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
    case OUT_OF_STOCK = 'out_of_stock';
}
