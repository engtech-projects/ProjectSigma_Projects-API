<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TimelineClassification: string
{
    use EnumHelper;

    case CURRENT_TIMELINE = 'current timeline';
    case PROPOSAL_TIMELINE = 'proposal timeline';
    case INTERNAL_TIMELINE = 'internal timeline';
}
