<?php

namespace App\Filament\Imports;

use App\Models\Invoice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class InvoiceImporter extends Importer
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('taxpayer_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('pph_type_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('pic_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('created_by')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('project_name'),
            ImportColumn::make('invoice_number')
                ->rules(['max:255']),
            ImportColumn::make('reference_number')
                ->rules(['max:255']),
            ImportColumn::make('input_status')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('payment_status')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('invoice_date')
                ->rules(['date']),
            ImportColumn::make('payment_date')
                ->rules(['date']),
            ImportColumn::make('base_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('gross_up_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('pph_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('take_home_pay')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('djp_tax_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('note')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Invoice
    {
        return new Invoice();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
