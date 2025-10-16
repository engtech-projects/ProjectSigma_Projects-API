<?php

namespace App\Enums\Traits;

trait EnumHelper
{
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }

        return $array;
    }

    public static function toArraySwapped(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }

        return $array;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
