<?php

namespace App\Filament\Imports;

use App\Models\Taxpayer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class TaxpayerImporter extends Importer
{
    protected static ?string $model = Taxpayer::class;

    protected static function formatTaxpayerName(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $name = str($value)
            ->lower()
            ->title()
            ->toString();

        // Singkatan yang harus selalu uppercase
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

    protected static function formatIdentityNumber(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return preg_replace('/\s+/', '', (string) $value);
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->guess([
                    'Keterangan / Nama',
                    'Nama',
                    'Name',
                ])
                ->castStateUsing(
                    fn(?string $state): ?string => static::formatTaxpayerName($state)
                )
                ->rules(['required', 'max:255']),

            ImportColumn::make('npwp')
                ->label('NPWP')
                ->guess(['NPWP'])
                ->castStateUsing(
                    fn($state) => static::formatIdentityNumber($state)
                )
                ->rules(['nullable', 'max:255']),

            ImportColumn::make('nik')
                ->label('NIK')
                ->guess(['NIK'])
                ->castStateUsing(
                    fn($state) => static::formatIdentityNumber($state)
                )
                ->rules(['nullable', 'max:255']),

            ImportColumn::make('address')
                ->label('Address')
                ->guess([
                    'Alamat',
                    'Address',
                ])
                ->rules(['nullable', 'max:255']),
        ];
    }

    public function resolveRecord(): Taxpayer
    {
        return new Taxpayer();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your taxpayer import has completed and '
            . Number::format($import->successful_rows)
            . ' '
            . str('row')->plural($import->successful_rows)
            . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '
                . Number::format($failedRowsCount)
                . ' '
                . str('row')->plural($failedRowsCount)
                . ' failed to import.';
        }

        return $body;
    }
}
