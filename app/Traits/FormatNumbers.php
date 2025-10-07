<?php

namespace App\Traits;

trait FormatNumbers
{
    /**
     * Format a numeric value with thousand separators and at least 2 decimal places.
     *
     * @param mixed $value The numeric value to format
     * @return string|null Formatted string or null if input is null/empty
     */
    public function formatDecimal(mixed $value): ?string
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
    /**
     * Format a model attribute using formatDecimal.
     *
     * @param string $attribute The attribute name to format
     * @return string|null Formatted attribute value
     */
    public function formatted(string $attribute): ?string
    {
        return $this->formatDecimal($this->{$attribute});
    }
}
