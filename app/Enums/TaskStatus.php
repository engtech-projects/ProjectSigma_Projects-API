<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TaskStatus: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
    case DONE = 'done';
}
