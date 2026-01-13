<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ProjectApprovalStatus: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DENIED = 'denied';
}
