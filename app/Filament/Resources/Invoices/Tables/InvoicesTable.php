<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Taxpayer;
use App\Models\Pic;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('taxpayer.name')
                    ->label('Wajib Pajak')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pic.name')
                    ->label('PIC')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project_name')
                    ->label('Nama Projek')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoice_date')
                    ->label('Tanggal Invoice')
                    ->date(format: 'd M Y')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Tanggal Pembayaran')
                    ->date(format: 'd M Y')
                    ->sortable(),
                TextColumn::make('pphType.code')
                    ->label('Jenis PPh')
                    ->searchable(),
                TextColumn::make('base_amount')
                    ->label('Nilai Dasar')
                    ->numeric()
                    ->sortable()
                    ->prefix('IDR '),
                TextColumn::make('pph_amount')
                    ->label('Nilai PPh')
                    ->numeric()
                    ->sortable()
                    ->prefix('IDR '),
                TextColumn::make('gross_up_amount')
                    ->label('Nilai Gross Up')
                    ->numeric()
                    ->sortable()
                    ->prefix('IDR ')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('take_home_pay')
                    ->label('Jumlah (THP)')
                    ->numeric()
                    ->sortable()
                    ->prefix('IDR '),
                TextColumn::make('djp_tax_amount')
                    ->label('Nilai Pajak DJP')
                    ->numeric()
                    ->sortable()
                    ->prefix('IDR ')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('reference_number')
                    ->label('Nomor Referensi')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('input_status')
                    ->label('Status Input')
                    ->boolean(),
                IconColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->boolean(),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('advanced')
                    ->label('Filter Lanjutan')
                    ->form([
                        Select::make('taxpayer_id')
                            ->label('Wajib Pajak')
                            ->options(
                                Taxpayer::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('pic_id')
                            ->label('PIC')
                            ->options(
                                Pic::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload(),

                        DatePicker::make('invoice_date_from')
                            ->label('Tanggal Invoice Dari'),

                        DatePicker::make('invoice_date_until')
                            ->label('Tanggal Invoice Sampai'),

                        DatePicker::make('payment_date_from')
                            ->label('Tanggal Pembayaran Dari'),

                        DatePicker::make('payment_date_until')
                            ->label('Tanggal Pembayaran Sampai'),

                        Select::make('pph_type_id')
                            ->label('Jenis PPh')
                            ->relationship('pphType', 'code')
                            ->searchable()
                            ->preload(),

                        TextInput::make('base_amount_min')
                            ->label('Nilai Dasar Minimum')
                            ->numeric(),

                        TextInput::make('base_amount_max')
                            ->label('Nilai Dasar Maksimum')
                            ->numeric(),

                        TextInput::make('pph_amount_min')
                            ->label('Nilai PPh Minimum')
                            ->numeric(),

                        TextInput::make('pph_amount_max')
                            ->label('Nilai PPh Maksimum')
                            ->numeric(),

                        TextInput::make('take_home_pay_min')
                            ->label('Jumlah (THP) Minimum')
                            ->numeric(),

                        TextInput::make('take_home_pay_max')
                            ->label('Jumlah (THP) Maksimum')
                            ->numeric(),

                        Select::make('input_status')
                            ->label('Status Input')
                            ->options([
                                1 => 'Aktif',
                                0 => 'Tidak Aktif',
                            ]),

                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                1 => 'Sudah Dibayar',
                                0 => 'Belum Dibayar',
                            ]),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {

                        return $query

                            ->when(
                                $data['taxpayer_id'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('taxpayer_id', $value)
                            )

                            ->when(
                                $data['pic_id'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('pic_id', $value)
                            )

                            ->when(
                                $data['invoice_date_from'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('invoice_date', '>=', $date)
                            )

                            ->when(
                                $data['invoice_date_until'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('invoice_date', '<=', $date)
                            )

                            ->when(
                                $data['payment_date_from'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('payment_date', '>=', $date)
                            )

                            ->when(
                                $data['payment_date_until'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('payment_date', '<=', $date)
                            )

                            ->when(
                                $data['pph_type_id'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('pph_type_id', $value)
                            )

                            ->when(
                                $data['base_amount_min'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('base_amount', '>=', $value)
                            )

                            ->when(
                                $data['base_amount_max'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('base_amount', '<=', $value)
                            )

                            ->when(
                                $data['pph_amount_min'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('pph_amount', '>=', $value)
                            )

                            ->when(
                                $data['pph_amount_max'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('pph_amount', '<=', $value)
                            )

                            ->when(
                                $data['take_home_pay_min'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('take_home_pay', '>=', $value)
                            )

                            ->when(
                                $data['take_home_pay_max'] ?? null,
                                fn(Builder $query, $value) =>
                                $query->where('take_home_pay', '<=', $value)
                            )

                            ->when(
                                $data['input_status'] !== null,
                                fn(Builder $query) =>
                                $query->where(
                                    'input_status',
                                    $data['input_status']
                                )
                            )

                            ->when(
                                $data['payment_status'] !== null,
                                fn(Builder $query) =>
                                $query->where(
                                    'payment_status',
                                    $data['payment_status']
                                )
                            );
                    })
                    ->indicateUsing(function (array $data): array {

                        $indicators = [];

                        if ($data['taxpayer_id'] ?? null) {
                            $indicators[] = 'Wajib Pajak';
                        }

                        if ($data['pic_id'] ?? null) {
                            $indicators[] = 'PIC';
                        }

                        if (($data['invoice_date_from'] ?? null) || ($data['invoice_date_until'] ?? null)) {
                            $indicators[] = 'Tanggal Invoice';
                        }

                        if (($data['payment_date_from'] ?? null) || ($data['payment_date_until'] ?? null)) {
                            $indicators[] = 'Tanggal Pembayaran';
                        }

                        if (($data['base_amount_min'] ?? null) || ($data['base_amount_max'] ?? null)) {
                            $indicators[] = 'Nilai Dasar';
                        }

                        if (($data['pph_amount_min'] ?? null) || ($data['pph_amount_max'] ?? null)) {
                            $indicators[] = 'Nilai PPh';
                        }

                        if (($data['take_home_pay_min'] ?? null) || ($data['take_home_pay_max'] ?? null)) {
                            $indicators[] = 'Jumlah (THP)';
                        }

                        if (($data['input_status'] ?? null) !== null) {
                            $indicators[] = 'Status Input';
                        }

                        if (($data['payment_status'] ?? null) !== null) {
                            $indicators[] = 'Status Pembayaran';
                        }

                        return $indicators;
                    }),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(1)   // outer shell is 1 col; sections control inner layout
            ->headerActions([
                ImportAction::make()
                    ->importer(InvoiceImporter::class)
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export Excel')
                        ->exporter(InvoiceExporter::class)
                        ->icon('heroicon-s-document-arrow-down'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
