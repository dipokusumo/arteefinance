<?php

namespace App\Filament\Resources\Pics\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PIC Information')->columnSpanFull()->schema([
                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->required()
                        ->email(),
                    TextInput::make('phone')
                        ->tel()
                        ->required(),
                ])
            ]);
    }
}
