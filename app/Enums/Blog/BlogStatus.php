<?php

namespace App\Enums\Blog;

use App\Enums\Enum;

enum BlogStatus: string
{
    use Enum;

    case DEACTIVE = 'deactive';
    case ACTIVE = 'active';
}
