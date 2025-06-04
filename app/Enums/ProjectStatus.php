<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ProjectStatus: string
{
    use EnumHelper;
    case OPEN = 'open';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case ONGOING = 'ongoing';
    case ARCHIVED = 'archived';
    case ONHOLD = 'on-hold';
    case CANCELLED = 'cancelled';
    case VOID = 'void';
    case DELETED = 'deleted';
    case DRAFT = 'draft';
    case MY_PROJECT = 'myProjects';
}
