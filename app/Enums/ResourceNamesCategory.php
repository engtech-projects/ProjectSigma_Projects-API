<?php

namespace App\Enums;

enum ResourceNamesCategory: string
{
    case INVENTORY = 'inventory';
    case SERVICE = 'service';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}


