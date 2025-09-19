<?php

namespace App\Traits;

trait FormatsNumbers
{
    public function getFormatted(string $field, int $decimals = 2): ?string
    {
        $value = $this->{$field} ?? null;
        return $value !== null ? number_format($value, $decimals) : null;
    }

    public function getFormattedValue($value, int $decimals = 2): ?string
    {
        return $value !== null ? number_format($value, $decimals) : null;
    }
}
