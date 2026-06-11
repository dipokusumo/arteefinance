<?php

namespace App\Filament\Resources\PphTypes\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PphTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50),
                TextColumn::make('factor')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),
                TextColumn::make('tax_rate')
                    ->label('Tax Rate')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                IconColumn::make('is_gross_up')
                    ->label('Gross Up')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
