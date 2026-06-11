<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
class FormatInput
{
    public static function formatTaxpayerName(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $name = str($value)
            ->squish()
            ->lower()
            ->title()
            ->toString();

        $abbreviations = [
            'Pt' => 'PT',
            'Pt.' => 'PT',
            'Cv' => 'CV',
            'Cv.' => 'CV',
            'Ud' => 'UD',
            'Ud.' => 'UD',
            'Pkp' => 'PKP',
            'Bumn' => 'BUMN',
            'Bumd' => 'BUMD',
        ];

        $words = preg_split('/\s+/', $name);

        foreach ($words as &$word) {
            $cleanWord = rtrim($word, '.');

            if (isset($abbreviations[$cleanWord])) {
                $word = $abbreviations[$cleanWord];
            }
        }

        // Tangani inisial seperti J.H, A.B.C, dll
        $name = implode(' ', $words);

        $name = preg_replace_callback(
            '/\b([A-Za-z])(?:\.([A-Za-z]))+\b/',
            function ($matches) {
                return strtoupper($matches[0]);
            },
            $name
        );

        return $name;
    }

    public static function formatIdentifyNumber(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\s+/', '', (string) $value);
    }

    
    public static function parseBooleanStatic(mixed $value): bool
    {
        if (blank($value)) {
            return false;
        }

        return in_array(
            strtoupper(trim((string) $value)),
            ['TRUE', '1', 'YES', 'Y'],
            true
        );
    }

    public static function parseDateStatic(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'date' => "Format tanggal '{$value}' tidak valid.",
            ]);
        }
    }
}
