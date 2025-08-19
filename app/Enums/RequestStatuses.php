<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum RequestStatuses: string
{
    use EnumHelper;
    case APPROVED = 'approved';
    case PENDING = 'pending';
    case DENIED = 'denied';
    case VOID = 'voided';

}
