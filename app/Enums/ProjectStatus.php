<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ProjectStatus: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
    case ONHOLD = 'on-hold';

}
