<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Schemas\Components\InlineEditColumn;
use App\Helpers\FormatInput;
use Illuminate\Support\Facades\Validator;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rule;
use Filament\Actions\ViewAction;

class UsersTable
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
                    ->sortable()
                    ->updateStateUsing(function ($state, $record) {

                        Validator::make(
                            ['email' => $state],
                            [
                                'email' => [
                                    'required',
                                    'email',
                                    Rule::unique('users', 'email')
                                        ->ignore($record->id),
                                ],
                            ]
                        )->validate();

                        $record->update([
                            'email' => $state,
                        ]);

                        return $state;
                    })
                    ->disabledClick(),
                TextColumn::make('role.name')
                    ->label('Roles')
                    ->badge()
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
