<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
