<?php

namespace App\Enums;

enum MarketingStage: string
{
    case DRAFT = 'draft';
    case PROPOSAL = 'proposal';
    case BIDDING = 'bidding';
    case AWARDED = 'awarded';
    case GENERATETOTSS = 'generate_to_tss';

    /**
     * Ordered flow of statuses.
     */
    public static function flow(): array
    {
        return [
            self::DRAFT,
            self::PROPOSAL,
            self::BIDDING,
            self::AWARDED,
            self::GENERATETOTSS,
        ];
    }

    /**
     * Get next status in the flow.
     */
    public function next(): ?self
    {
        $flow = self::flow();
        $index = array_search($this, $flow);

        return $flow[$index + 1] ?? null;
    }

    /**
     * Get previous status in the flow.
     */
    public function previous(): ?self
    {
        $flow = self::flow();
        $index = array_search($this, $flow);

        return $index > 0 ? $flow[$index - 1] : null;
    }

    /**
     * All raw string values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
