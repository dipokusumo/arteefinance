<?php

namespace App\Filament\Resources\PphTypes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class PphTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PPh Type Information')->columnSpanFull()->schema([
                    TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->rows(5),
                    TextInput::make('tax_rate')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->helperText('Enter the tax rate as a percentage (e.g., 10 for 10%) and use comma as decimal separator if needed (e.g., 2,5 for 2.5%).'),
                ])
            ]);
    }
}
