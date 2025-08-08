<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ResourceNamesCategory: string
{
    use EnumHelper;
    case INVENTORY = 'inventory';
    case SERVICE = 'service';
}


