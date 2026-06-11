<?php

namespace App\Filament\Resources\Pics\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Schemas\Components\InlineEditColumn;
use App\Helpers\FormatInput;
use Illuminate\Support\Facades\Validator;
use Filament\Actions\ViewAction;

class PicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                InlineEditColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->updateStateUsing(function ($state, $record) {
                        $record->update([
                            'name' => FormatInput::formatTaxpayerName($state),
                        ]);

                        return $state;
                    })
                    ->disabledClick(),
                InlineEditColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->updateStateUsing(function ($state, $record) {
                        Validator::make(
                            ['email' => $state],
                            [
                                'email' => [
                                    'nullable',
                                    'email',
                                ],
                            ]
                        )->validate();

                        $record->update([
                            'email' => $state,
                        ]);

                        return $state;
                    })
                    ->disabledClick(),
                InlineEditColumn::make('phone')
                    ->searchable()
                    ->updateStateUsing(function ($state, $record) {
                        $record->update([
                            'phone' => FormatInput::formatIdentifyNumber($state),
                        ]);

                        return $state;
                    })
                    ->disabledClick(),
            ])
            ->filters([
                //
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
