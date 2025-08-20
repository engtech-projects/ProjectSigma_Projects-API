<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ChangeRequestStatus: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
}
