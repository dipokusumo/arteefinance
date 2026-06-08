<?php

namespace App\Filament\Resources\Taxpayers\Tables;

use App\Filament\Imports\TaxpayerImporter;
use App\Filament\Schemas\Components\InlineEditColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class TaxpayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                InlineEditColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->disabledClick(),

                InlineEditColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable()
                    ->disabledClick(),

                InlineEditColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('address')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(TaxpayerImporter::class)
            ])
            ->recordActions([
                ViewAction::make(),
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
