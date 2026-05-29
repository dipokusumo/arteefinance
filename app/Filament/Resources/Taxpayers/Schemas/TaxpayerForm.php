<?php

namespace App\Filament\Resources\Taxpayers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class TaxpayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Taxpayer Information')->columnSpanFull()->schema([
                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('npwp')
                        ->label('NPWP'),
                    TextInput::make('nik')
                        ->label('NIK'),
                    TextInput::make('address'),
                ])
            ]);
    }
}
