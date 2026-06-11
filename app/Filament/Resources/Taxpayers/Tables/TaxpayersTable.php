<?php

namespace App\Filament\Resources\Taxpayers\Tables;

use App\Filament\Imports\TaxpayerImporter;
use App\Filament\Schemas\Components\InlineEditColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Helpers\FormatInput;

use Filament\Actions\ViewAction;

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
                    ->disabledClick()
                    ->updateStateUsing(function ($state, $record) {
                        $record->update([
                            'name' => FormatInput::formatTaxpayerName($state),
                        ]);

                        return $state;
                    }),

                InlineEditColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable()
                    ->disabledClick()
                    ->updateStateUsing(function ($state, $record) {
                        $record->update([
                            'npwp' => FormatInput::formatIdentifyNumber($state),
                        ]);

                        return $state;
                    }),

                InlineEditColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->disabledClick()
                    ->updateStateUsing(function ($state, $record) {
                        $record->update([
                            'nik' => FormatInput::formatIdentifyNumber($state),
                        ]);

                        return $state;
                    }),

                InlineEditColumn::make('address')
                    ->limit(50)
                    ->searchable()
                    ->disabledClick(),
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
