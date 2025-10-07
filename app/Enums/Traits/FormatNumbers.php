<?php

namespace App\Enums\Traits;

trait FormatNumbers
{
    public function formatDecimal($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = (string) $value;
        if (strpos($value, '.') !== false) {
            [$whole, $decimal] = explode('.', $value, 2);
            $wholeFormatted = number_format((int) $whole);
            $decimal = rtrim($decimal, '0');
            if (strlen($decimal) < 2) {
                $decimal = str_pad($decimal, 2, '0');
            }
            return "{$wholeFormatted}.{$decimal}";
        }
        return number_format((int) $value) . '.00';
    }
    public function formatted($attribute)
    {
        return $this->formatDecimal($this->{$attribute});
    }
}
