<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum Accessibility: string
{
    use EnumHelper;
    case SUPERADMIN = 'project sigma:super admin';
}
