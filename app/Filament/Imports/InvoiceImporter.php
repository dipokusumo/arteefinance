<?php

namespace App\Filament\Imports;

use App\Helpers\ParseCurrency;
use App\Models\Invoice;
use App\Models\Pic;
use App\Models\PphType;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class InvoiceImporter extends Importer
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('taxpayer_id')
                ->label('Wajib Pajak')
                ->requiredMapping()
                ->guess([
                    'Keterangan / Nama',
                ]),

            ImportColumn::make('pph_type_id')
                ->label('Jenis PPh')
                ->requiredMapping()
                ->guess([
                    'Status'
                ])
                ->castStateUsing(function ($state) {
                    $pphType = PphType::query()
                        ->whereRaw('LOWER(code) = ?', [
                            strtolower(trim((string) $state))
                        ])
                        ->first();

                    if (!$pphType) {
                        throw ValidationException::withMessages([
                            'pph_type_id' => "PPh Type '{$state}' tidak ditemukan.",
                        ]);
                    }

                    return $pphType->id;
                }),

            ImportColumn::make('pic_id')
                ->label('PIC'),

            ImportColumn::make('project_name')
                ->label('Nama Projek')
                ->requiredMapping()
                ->guess([
                    'Project / campaign'
                ]),

            ImportColumn::make('invoice_number')
                ->label('Nomer Invoice')
                ->guess([
                    'Nomor Invoice'
                ]),

            ImportColumn::make('input_status')
                ->label('Status Input')
                ->guess([
                    'Di-Input'
                ])
                ->castStateUsing(fn($state) => static::parseBooleanStatic($state)),

            ImportColumn::make('payment_status')
                ->label('Status Pembayaran')
                ->guess([
                    'Di-Bayar'
                ])
                ->castStateUsing(fn($state) => static::parseBooleanStatic($state)),

            ImportColumn::make('invoice_date')
                ->label('Tanggal Invoice')
                ->castStateUsing(fn($state) => static::parseDateStatic($state)),

            ImportColumn::make('payment_date')
                ->label('Tanggal Pembayaran')
                ->castStateUsing(fn($state) => static::parseDateStatic($state)),

            ImportColumn::make('base_amount')
                ->label('Nilai Dasar')
                ->requiredMapping()
                ->castStateUsing(fn($state) => ParseCurrency::parseCurrency($state)),

            ImportColumn::make('note')
                ->label('Catatan')
                ->guess([
                    'Keterangan'
                ]),
        ];
    }

    public function resolveRecord(): Invoice
    {
        $invoice = new Invoice();

        $invoice->created_by = Filament::auth()->id();

        return $invoice;
    }

    protected static function formatTaxpayerName(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $name = str($value)
            ->squish()
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

    protected function beforeValidate(): void
    {
        $name = static::formatTaxpayerName(
            $this->originalData['Keterangan / Nama'] ?? null
        );

        $taxpayer = Taxpayer::query()
            ->whereRaw('LOWER(name) = ?', [strtolower(trim($name))])
            ->first();

        if (!$taxpayer) {
            $taxpayer = Taxpayer::updateOrCreate(
                [
                    'name' => $name,
                ],
                [
                    'npwp' => static::formatIdentityNumber($this->originalData['NPWP'] ?? null),
                    'nik' => static::formatIdentityNumber($this->originalData['NIK'] ?? null),
                    'address' => $this->originalData['Alamat'] ?? null,
                ]
            );
        }

        $this->data['taxpayer_id'] = $taxpayer->id;

        $picName = trim(
            (string) ($this->originalData['PIC'] ?? '')
        );

        if (!blank($picName)) {

            $pic = Pic::query()
                ->whereRaw('LOWER(name) = ?', [
                    strtolower($picName),
                ])
                ->first();

            if (!$pic) {
                $pic = Pic::updateOrCreate(
                    [
                        'name' => $picName,
                    ],
                    [
                        'email' => null,
                        'phone' => null,
                    ]
                );
            }

            $this->data['pic_id'] = $pic->id;
        } else {
            $this->data['pic_id'] = null;
        }
    }

    protected static function formatIdentityNumber(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return preg_replace('/\s+/', '', (string) $value);
    }

    protected static function parseBooleanStatic(mixed $value): bool
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

    protected static function parseDateStatic(mixed $value): ?string
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

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice import has completed and '
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
