<?php

namespace App\Filament\Resources\PphTypes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                    TextInput::make('factor')
                        ->label('Factor')
                        ->required()
                        ->numeric()
                        ->inputMode('decimal')
                        ->step(0.001)
                        ->helperText('Enter the factor as a decimal number (e.g., 1.000 for no adjustment, 0.975 for a 2.5% reduction). Use comma as decimal separator if needed (e.g., 1,111 for 1.111).'),
                    TextInput::make('tax_rate')
                        ->label('Tax Rate')
                        ->required()
                        ->numeric()
                        ->inputMode('decimal')
                        ->step(0.01)
                        ->suffix('%')
                        ->helperText('Enter the tax rate as a percentage (e.g., 10 for 10%) and use comma as decimal separator if needed (e.g., 2,5 for 2.5%).'),
                    Toggle::make('is_gross_up')
                        ->label('Is Gross Up')
                        ->default(false)
                        ->helperText('Enable this if the PPh type is a gross-up type. When enabled, the system will calculate the gross-up amount and take-home pay accordingly.'),
                ])
            ]);
    }
}
