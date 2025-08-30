<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use Illuminate\Validation\ValidationException;

enum ProjectStage: string
{
    use EnumHelper;
    case DRAFT = 'draft';
    case PROPOSAL = 'proposal';
    case BIDDING = 'bidding';
    case AWARDED = 'awarded';
    case GENERATETOTSS = 'generate_to_tss';
    case ARCHIVED = 'archived';

    public static function flow(): array
    {
        return [
            self::DRAFT,
            self::PROPOSAL,
            self::BIDDING,
            self::AWARDED,
            self::GENERATETOTSS,
            self::ARCHIVED,
        ];
    }

    public static function validateTransition(string $oldStage, string $newStage): ProjectStage
    {
        $flowValues = self::values();
        $oldIndex = array_search($oldStage, $flowValues, true);
        $newIndex = array_search($newStage, $flowValues, true);
        if ($oldIndex === false || $newIndex === false) {
            throw ValidationException::withMessages([
                'stage' => 'Invalid stage provided.',
            ]);
        }
        if ($newIndex < $oldIndex) {
            throw ValidationException::withMessages([
                'stage' => 'Cannot move to an earlier stage.',
            ]);
        }
        if ($newStage === self::GENERATETOTSS->value && $oldStage !== self::AWARDED->value) {
            throw ValidationException::withMessages([
                'stage' => 'You must award the project before generating to TSS.',
            ]);
        }

        return self::from($newStage);
    }
}
