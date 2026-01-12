<?php

namespace App\Traits;

trait FormatNumbers
{
    public function getDecimalPlaces($number): int
    {
        $parts = explode('.', (string)$number);
        if (count($parts) === 2) {
            return strlen(rtrim($parts[1], '0'));
        }
        return 0;
    }
    public function formatted($number, $decimals = 2): string
    {
        return number_format($number, max($this->getDecimalPlaces($number), $decimals), '.', ',');
    }
    public function formatNumber($value)
    {
        $value = (float) $value;
        $formatted = number_format($value, 2, '.', ','); // adds commas, 2 decimals
        // remove unnecessary ".00"
        return substr($formatted, -3) === '.00' ? number_format((int)$value, 0, '', ',') : $formatted;
    }
}
