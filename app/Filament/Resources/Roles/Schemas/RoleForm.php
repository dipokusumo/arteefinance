<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Information')->columnSpanFull()->schema([
                    TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                    Textarea::make('description')->maxLength(65535)->rows(5),
                ])
            ]);
    }
}
