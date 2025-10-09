<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum SourceType: string
{
    use EnumHelper;

    case RESOURCE = 'resource';
    case CUSTOM = 'custom';
}
