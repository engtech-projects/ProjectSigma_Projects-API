<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TssStage: string
{
    use EnumHelper;
    case PENDING = 'pending';
    case DUPA_PREPARATION = 'dupa_preparation';
    case DUPA_TIMELINE = 'dupa_timeline';
    case LIVE = 'live';
    case COMPLETED = 'completed';

    public static function flow(): array
    {
        return [
            self::PENDING,
            self::DUPA_PREPARATION,
            self::DUPA_TIMELINE,
            self::LIVE,
            self::COMPLETED,
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

}
