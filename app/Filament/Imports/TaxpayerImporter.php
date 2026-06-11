<?php

namespace App\Filament\Imports;

use App\Models\Taxpayer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use App\Helpers\FormatInput;

class TaxpayerImporter extends Importer
{
    protected static ?string $model = Taxpayer::class;

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
                    fn(?string $state): ?string => FormatInput::formatTaxpayerName($state)
                )
                ->rules(['required', 'max:255']),

            ImportColumn::make('npwp')
                ->label('NPWP')
                ->guess(['NPWP'])
                ->castStateUsing(
                    fn($state) => FormatInput::formatIdentifyNumber($state)
                )
                ->rules(['nullable', 'max:255']),

            ImportColumn::make('nik')
                ->label('NIK')
                ->guess(['NIK'])
                ->castStateUsing(
                    fn($state) => FormatInput::formatIdentifyNumber($state)
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
        return Taxpayer::firstOrNew([
            'name' => $this->data['name'] ?? null,
        ]);
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
