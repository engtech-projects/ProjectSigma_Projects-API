<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ResourceStatus: string
{
    use EnumHelper;
    case DRAFT = 'draft';
    case ITEM = "item";
}
