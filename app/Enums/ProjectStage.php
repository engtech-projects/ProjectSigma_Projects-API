<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ProjectStage: string
{
    use EnumHelper;
    case PROPOSAL = 'proposal';
    case AWARDED = 'awarded';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case BIDDING = 'bidding';

    public static function flow(): array
    {
        return [
            self::DRAFT,
            self::PROPOSAL,
            self::BIDDING,
            self::AWARDED,
            self::ARCHIVED,
        ];
    }
}
