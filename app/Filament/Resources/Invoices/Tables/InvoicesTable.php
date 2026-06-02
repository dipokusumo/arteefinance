<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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



class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('taxpayer.name')
                    ->label('Wajib Pajak')
                    ->sortable(),
                TextColumn::make('pic.name')
                    ->label('PIC')
                    ->sortable(),
                TextColumn::make('project_name')
                    ->label('Nama Projek')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('input_status')
                    ->boolean(),
                IconColumn::make('payment_status')
                    ->boolean(),
                TextColumn::make('invoice_date')
                    ->date($format = 'd/m/Y')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->date($format = 'd/m/Y')
                    ->sortable(),
                TextColumn::make('pphType.code')
                    ->searchable(),
                TextColumn::make('base_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gross_up_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pph_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('take_home_pay')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('djp_tax_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('reference_number')
                    ->searchable(),
                TextColumn::make('note')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
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
                    ->label('Jenis PPH')
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
                        DatePicker::make('from')->label('Invoice Date Dari'),
                        DatePicker::make('until')->label('Invoice Date Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('invoice_date', '>=', $date))
                            ->when($data['until'] ?? null, fn($q, $date) => $q->whereDate('invoice_date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = 'Invoice Date Dari ' . $data['from'];
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = 'Invoice Date Sampai ' . $data['until'];
                        }

                        return $indicators;
                    }),
                Filter::make('payment_date_range')
                    ->label('Tanggal Pembayaran')
                    ->form([
                        DatePicker::make('from')->label('Payment Date Dari'),
                        DatePicker::make('until')->label('Payment Date Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('payment_date', '>=', $date))
                            ->when($data['until'] ?? null, fn($q, $date) => $q->whereDate('payment_date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = 'Payment Date Dari ' . $data['from'];
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = 'Payment Date Sampai ' . $data['until'];
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
