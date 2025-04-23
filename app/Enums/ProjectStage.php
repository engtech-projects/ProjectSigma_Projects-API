<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ProjectStage: string
{
    use EnumHelper;
    case PROPOSAL = 'proposal';
    case AWARDED = 'awarded';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            ProjectStage::PROPOSAL => 'proposal',
            ProjectStage::AWARDED => 'awarded',
        };
    }
}
