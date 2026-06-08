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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Taxpayer;
use App\Models\Pic;
use App\Filament\Exports\InvoiceExporter;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Filters\Indicator;
use App\Filament\Imports\InvoiceImporter;
use Filament\Actions\ImportAction;


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

                        // ── Section 1: Entitas ──────────────────────────
                        Section::make('Entitas')
                            ->icon('heroicon-o-user-group')
                            ->columns(2)
                            ->schema([
                                Select::make('taxpayer_id')
                                    ->label('Wajib Pajak')
                                    ->options(
                                        Taxpayer::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Semua Wajib Pajak'),

                                Select::make('pic_id')
                                    ->label('PIC')
                                    ->options(
                                        Pic::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Semua PIC'),
                            ]),

                        // ── Section 2: Tanggal ──────────────────────────
                        Section::make('Rentang Tanggal')
                            ->icon('heroicon-o-calendar-days')
                            ->columns(2)
                            ->schema([
                                Grid::make(2)->schema([
                                    DatePicker::make('invoice_date_from')
                                        ->label('Invoice: Dari')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->placeholder('Pilih tanggal'),

                                    DatePicker::make('invoice_date_until')
                                        ->label('Invoice: Sampai')
                                        ->native(false)
                                        ->displayFormat('d/mm/Y')
                                        ->placeholder('Pilih tanggal')

                                ]),

                                Grid::make(2)->schema([
                                    DatePicker::make('payment_date_from')
                                        ->label('Pembayaran: Dari')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->placeholder('Pilih tanggal'),

                                    DatePicker::make('payment_date_until')
                                        ->label('Pembayaran: Sampai')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->placeholder('Pilih tanggal')

                                ]),
                            ])
                            ->columnSpanFull(),

                        // ── Section 3: Pajak & Nilai ────────────────────
                        Section::make('Pajak & Nilai')
                            ->icon('heroicon-o-banknotes')
                            ->columns(3)
                            ->schema([
                                Select::make('pph_type_id')
                                    ->label('Jenis PPh')
                                    ->relationship('pphType', 'code')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Semua Jenis')
                                    ->columnSpan(1),

                                TextInput::make('base_amount_min')
                                    ->label('Nilai Dasar: Min')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),

                                TextInput::make('base_amount_max')
                                    ->label('Nilai Dasar: Maks')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('Tidak terbatas'),

                                // Spacer to align next row
                                TextEntry::make('spacer0')
                                    ->hiddenLabel()
                                    ->columnSpan(1),

                                TextInput::make('pph_amount_min')
                                    ->label('PPh: Min')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),

                                TextInput::make('pph_amount_max')
                                    ->label('PPh: Maks')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('Tidak terbatas'),

                                TextEntry::make('spacer1')
                                    ->hiddenLabel()
                                    ->columnSpan(1),

                                TextInput::make('take_home_pay_min')
                                    ->label('THP: Min')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),

                                TextInput::make('take_home_pay_max')
                                    ->label('THP: Maks')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('Tidak terbatas'),
                            ]),

                        // ── Section 4: Status ───────────────────────────
                        Section::make('Status')
                            ->icon('heroicon-o-flag')
                            ->columns(2)
                            ->schema([
                                Select::make('input_status')
                                    ->label('Status Input')
                                    ->options([
                                        1 => 'Aktif',
                                        0 => 'Tidak Aktif',
                                    ])
                                    ->placeholder('Semua Status Input'),

                                Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        1 => 'Sudah Dibayar',
                                        0 => 'Belum Dibayar',
                                    ])
                                    ->placeholder('Semua Status Bayar'),
                            ]),
                    ])
                    ->columnSpanFull()      // filter form takes full width
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['taxpayer_id'] ?? null) {
                            $query->where('taxpayer_id', $data['taxpayer_id']);
                        }

                        if ($data['pic_id'] ?? null) {
                            $query->where('pic_id', $data['pic_id']);
                        }

                        if ($data['invoice_date_from'] ?? null) {
                            $query->whereDate('invoice_date', '>=', $data['invoice_date_from']);
                        }
                        if ($data['invoice_date_until'] ?? null) {
                            $query->whereDate('invoice_date', '<=', $data['invoice_date_until']);
                        }

                        if ($data['payment_date_from'] ?? null) {
                            $query->whereDate('payment_date', '>=', $data['payment_date_from']);
                        }
                        if ($data['payment_date_until'] ?? null) {
                            $query->whereDate('payment_date', '<=', $data['payment_date_until']);
                        }

                        if ($data['pph_type_id'] ?? null) {
                            $query->where('pph_type_id', $data['pph_type_id']);
                        }

                        if (($data['base_amount_min'] ?? null) !== null) {
                            $query->where('base_amount', '>=', $data['base_amount_min']);
                        }
                        if (($data['base_amount_max'] ?? null) !== null) {
                            $query->where('base_amount', '<=', $data['base_amount_max']);
                        }

                        if (($data['pph_amount_min'] ?? null) !== null) {
                            $query->where('pph_amount', '>=', $data['pph_amount_min']);
                        }
                        if (($data['pph_amount_max'] ?? null) !== null) {
                            $query->where('pph_amount', '<=', $data['pph_amount_max']);
                        }

                        if (($data['take_home_pay_min'] ?? null) !== null) {
                            $query->where('take_home_pay', '>=', $data['take_home_pay_min']);
                        }
                        if (($data['take_home_pay_max'] ?? null) !== null) {
                            $query->where('take_home_pay', '<=', $data['take_home_pay_max']);
                        }

                        if (isset($data['input_status']) && $data['input_status'] !== null) {
                            $query->where('input_status', $data['input_status']);
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['taxpayer_id'] ?? null)
                            $indicators[] = Indicator::make('Wajib Pajak')->removeField('taxpayer_id');

                        if ($data['pic_id'] ?? null)
                            $indicators[] = Indicator::make('PIC')->removeField('pic_id');

                        if (($data['invoice_date_from'] ?? null) || ($data['invoice_date_until'] ?? null))
                            $indicators[] = Indicator::make('Tanggal Invoice');

                        if (($data['payment_date_from'] ?? null) || ($data['payment_date_until'] ?? null))
                            $indicators[] = Indicator::make('Tanggal Pembayaran');

                        if ($data['pph_type_id'] ?? null)
                            $indicators[] = Indicator::make('Jenis PPh')->removeField('pph_type_id');

                        if (($data['base_amount_min'] ?? null) || ($data['base_amount_max'] ?? null))
                            $indicators[] = Indicator::make('Nilai DPP');

                        if (($data['pph_amount_min'] ?? null) || ($data['pph_amount_max'] ?? null))
                            $indicators[] = Indicator::make('Nilai PPh');

                        if (($data['take_home_pay_min'] ?? null) || ($data['take_home_pay_max'] ?? null))
                            $indicators[] = Indicator::make('Jumlah THP');

                        if (isset($data['input_status']) && $data['input_status'] !== null)
                            $indicators[] = Indicator::make('Status Input')->removeField('input_status');

                        if (isset($data['payment_status']) && $data['payment_status'] !== null)
                            $indicators[] = Indicator::make('Status Pembayaran')->removeField('payment_status');

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
