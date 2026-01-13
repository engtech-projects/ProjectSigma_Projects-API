<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum RevisionStatus: string
{
    use EnumHelper;
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
}
