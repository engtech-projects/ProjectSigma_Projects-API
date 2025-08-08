<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ResourceNamesCategory: string
{
    case INVENTORY = 'inventory';
    case SERVICE = 'service';

    use EnumHelper;
}


