<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ChangeRequestType: string
{
    use EnumHelper;
    case SCOPE_CHANGE = 'scope_change';
    case DEADLINE_EXTENSION = 'deadline_extension';
    case BUDGET_ADJUSTMENT = 'budget_adjustment';
}
