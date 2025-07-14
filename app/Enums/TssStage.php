<?php

namespace App\Enums;

enum TssStage: string
{
    case PENDING = 'pending';
    case AWARDED = 'awarded';
    case ARCHIVED = 'archived';

    public static function flow(): array
    {
        return [
            self::PENDING,
            self::AWARDED,
            self::ARCHIVED,
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
