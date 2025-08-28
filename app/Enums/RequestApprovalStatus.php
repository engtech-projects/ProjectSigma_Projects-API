<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use Illuminate\Validation\Rules\Enum;

final class RequestApprovalStatus extends Enum
{
    use EnumHelper;

    public const APPROVED = 'Approved';

    public const PENDING = 'Pending';

    public const DENIED = 'Denied';
}
