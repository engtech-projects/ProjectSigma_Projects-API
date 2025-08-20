<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ChangeRequestApprovalDecision: string
{
    use EnumHelper;
    case APPROVED = 'approved';
    case DECLINED = 'declined';
}
