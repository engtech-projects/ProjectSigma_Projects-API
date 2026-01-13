<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TimelineClassification: string
{
    use EnumHelper;

    case CURRENT_TIMELINE = 'current_timeline';
    case PROPOSAL_TIMELINE = 'proposal_timeline';
    case INTERNAL_TIMELINE = 'internal_timeline';
}
