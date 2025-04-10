<?php

namespace App\Enums;

enum ProjectStage: string
{
    case PROPOSAL = 'proposal';
    case AWARDED = 'awarded';

    public function label(): string
    {
        return match ($this) {
            ProjectStage::PROPOSAL => 'proposal',
            ProjectStage::AWARDED => 'awarded',
        };
    }
}
