<?php
namespace App\Enums;
use App\Enums\Traits\EnumHelper;
enum TssStatus: string
{
    use EnumHelper;
    case APPROVED = 'Approved';
    case PENDING = 'Pending';
    case ONGOING = 'Ongoing';
    case DENIED = 'Denied';
    case VOIDED = 'Voided';
}
