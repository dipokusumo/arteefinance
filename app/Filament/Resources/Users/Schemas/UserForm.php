<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')->columnSpanFull()->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->password()
                        ->required(),
                    Select::make('role_id')
                        ->relationship('role', 'name')
                        ->preload(),
                ])
            ]);
    }
}
