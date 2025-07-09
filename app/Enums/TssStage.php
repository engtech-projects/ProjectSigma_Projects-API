<?php

namespace App\Enums;

enum TssStage: string
{
    case Pending = 'pending';
    case Awarded = 'awarded';
    case Archived = 'archived';

    public static function flow(): array
    {
        return [
            self::Pending,
            self::Awarded,
            self::Archived,
        ];
    }

    public function next(): ?self
    {
        $flow = self::flow();
        $index = array_search($this, $flow);

        return $flow[$index + 1] ?? null;
    }

    public function previous(): ?self
    {
        $flow = self::flow();
        $index = array_search($this, $flow);

        return $index > 0 ? $flow[$index - 1] : null;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
