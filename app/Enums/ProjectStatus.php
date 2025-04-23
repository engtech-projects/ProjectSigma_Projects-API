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
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';
    case ONHOLD = 'on-hold';
    case CANCELLED = 'cancelled';
    case VOID = 'void';
    case DELETED = 'deleted';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            ProjectStatus::OPEN => 'open',
            ProjectStatus::SUBMITTED => 'submitted',
            ProjectStatus::APPROVED => 'approved',
            ProjectStatus::ONGOING => 'ongoing',
            ProjectStatus::COMPLETED => 'completed',
            ProjectStatus::ARCHIVED => 'archived',
            ProjectStatus::ONHOLD => 'on-hold',
            ProjectStatus::CANCELLED => 'cancelled',
            ProjectStatus::VOID => 'void',
            ProjectStatus::DELETED => 'deleted',
        };
    }
}
