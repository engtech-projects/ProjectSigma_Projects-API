<?php

namespace App\Enums;

enum MarketingStage: string
{
    case Draft = 'draft';
    case Proposal = 'proposal';
    case Bidding = 'bidding';
    case Awarded = 'awarded';

    /**
     * Ordered flow of statuses.
     */
    public static function flow(): array
    {
        return [
            self::Draft,
            self::Proposal,
            self::Bidding,
            self::Awarded,
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
