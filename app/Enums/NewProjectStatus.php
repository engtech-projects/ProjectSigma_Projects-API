<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum NewProjectStatus: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';
}


