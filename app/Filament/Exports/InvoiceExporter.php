<?php

namespace App\Filament\Exports;

use App\Models\Invoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Carbon\Carbon;

class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('taxpayer.name')
                ->label('Wajib Pajak'),
            ExportColumn::make('project_name')
                ->label('Nama Projek'),
            ExportColumn::make('pic.name')
                ->label('PIC'),
            ExportColumn::make('input_status')
                ->label('Status Input')
                ->formatStateUsing(
                    fn(bool $state): string =>
                    $state ? 'Sudah Diinput' : 'Belum Diinput'
                ),
            ExportColumn::make('payment_status')
                ->label('Status Pembayaran')
                ->formatStateUsing(
                    fn(bool $state): string =>
                    $state ? 'Sudah Dibayar' : 'Belum Dibayar'
                ),
            ExportColumn::make('pphType.code')
                ->label('Jenis PPh'),
            ExportColumn::make('reference_number')
                ->label('Nomor Referensi'),
            ExportColumn::make('invoice_number')
                ->label('Nomor Invoice'),
            ExportColumn::make('invoice_date')
                ->label('Tanggal Invoice')
                ->formatStateUsing(
                    fn($state) => $state
                        ? Carbon::parse($state)->format('d M Y')
                        : '-'
                ),
            ExportColumn::make('payment_date')
                ->label('Tanggal Pembayaran')
                ->formatStateUsing(
                    fn($state) => $state
                        ? Carbon::parse($state)->format('d M Y')
                        : '-'
                ),
            ExportColumn::make('taxpayer.npwp')
                ->label('NPWP'),
            ExportColumn::make('taxpayer.nik')
                ->label('NIK'),
            ExportColumn::make('taxpayer.address')
                ->label('Alamat Wajib Pajak'),
            ExportColumn::make('base_amount')
                ->label('Nilai Dasar'),
            ExportColumn::make('gross_up_amount')
                ->label('Nilai Gross Up'),
            ExportColumn::make('pph_amount')
                ->label('Nilai PPh'),
            ExportColumn::make('take_home_pay')
                ->label('Jumlah (THP)'),
            ExportColumn::make('djp_tax_amount')
                ->label('Nilai Pajak DJP'),
            ExportColumn::make('note')
                ->label('Catatan'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada')
                ->formatStateUsing(
                    fn($state) => $state
                        ? Carbon::parse($state)->format('d M Y H:i:s')
                        : '-'
                ),
            ExportColumn::make('updated_at')
                ->label('Diupdate Pada')
                ->formatStateUsing(
                    fn($state) => $state
                        ? Carbon::parse($state)->format('d M Y H:i:s')
                        : '-'
                ),
            ExportColumn::make('creator.name')
                ->label('Dibuat Oleh'),
        ];
    }

    public function getFileName(Export $export): string
    {
        return 'invoices_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your invoice export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
