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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Exports\InvoiceExporter;



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
                \Filament\Tables\Filters\SelectFilter::make('taxpayer_id')
                    ->label('Wajib Pajak')
                    ->relationship('taxpayer', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('pic_id')
                    ->label('PIC')
                    ->relationship('pic', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('pph_type_id')
                    ->label('Jenis PPh')
                    ->relationship('pphType', 'code')
                    ->searchable()
                    ->preload(),
                Filter::make('project_name')
                    ->label('Nama Projek')
                    ->form([
                        TextInput::make('value')
                            ->label('Nama Projek')
                            ->placeholder('Cari nama projek'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['value'] ?? null, fn($q, $value) => $q->where('project_name', 'like', '%' . $value . '%'));
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!($data['value'] ?? null)) {
                            return [];
                        }

                        return ['Projek: ' . $data['value']];
                    }),
                \Filament\Tables\Filters\TernaryFilter::make('input_status')
                    ->label('Status Input')
                    ->trueLabel('Input')
                    ->falseLabel('Belum Input'),
                \Filament\Tables\Filters\TernaryFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->trueLabel('Lunas')
                    ->falseLabel('Belum Lunas'),
                Filter::make('invoice_date_range')
                    ->label('Tanggal Invoice')
                    ->form([
                        DatePicker::make('from')->label('Tanggal Invoice Dari'),
                        DatePicker::make('until')->label('Tanggal Invoice Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('invoice_date', '>=', $date))
                            ->when($data['until'] ?? null, fn($q, $date) => $q->whereDate('invoice_date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = 'Tanggal Invoice Dari ' . $data['from'];
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = 'Tanggal Invoice Sampai ' . $data['until'];
                        }

                        return $indicators;
                    }),
                Filter::make('payment_date_range')
                    ->label('Tanggal Pembayaran')
                    ->form([
                        DatePicker::make('from')->label('Tanggal Pembayaran Dari'),
                        DatePicker::make('until')->label('Tanggal Pembayaran Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('payment_date', '>=', $date))
                            ->when($data['until'] ?? null, fn($q, $date) => $q->whereDate('payment_date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = 'Tanggal Pembayaran Dari ' . $data['from'];
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = 'Tanggal Pembayaran Sampai ' . $data['until'];
                        }

                        return $indicators;
                    }),

                \Filament\Tables\Filters\SelectFilter::make('creator_id')
                    ->label('Dibuat Oleh')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
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
