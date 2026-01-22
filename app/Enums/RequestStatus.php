<?php
namespace App\Enums;
use App\Enums\Traits\EnumHelper;
enum RequestStatus: string
{
    use EnumHelper;
    case APPROVED = 'Approved';
    case PENDING = 'Pending';
    case ONGOING = 'Ongoing';
    case DENIED = 'Denied';
    case VOIDED = 'Voided';
    case REVISED = 'Revised';
}
