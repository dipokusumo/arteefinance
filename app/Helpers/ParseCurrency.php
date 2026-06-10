<?php

namespace App\Helpers;

class ParseCurrency
{
    public static function parseCurrency(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (float) preg_replace('/[^0-9-]/', '', (string) $value);
    }

    public static function formatIDR(float|int $value): string
    {

        return number_format((int) round($value), 0, '.', '.');
    }
}
