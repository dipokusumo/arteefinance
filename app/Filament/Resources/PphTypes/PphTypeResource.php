<?php

namespace App\Filament\Resources\PphTypes;

use App\Filament\Resources\PphTypes\Pages\CreatePphType;
use App\Filament\Resources\PphTypes\Pages\EditPphType;
use App\Filament\Resources\PphTypes\Pages\ListPphTypes;
use App\Filament\Resources\PphTypes\Schemas\PphTypeForm;
use App\Filament\Resources\PphTypes\Tables\PphTypesTable;
use App\Models\PphType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PphTypeResource extends Resource
{
    protected static ?string $model = PphType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 8000;

    protected static ?string $recordTitleAttribute = 'PPh Type';

    public static function form(Schema $schema): Schema
    {
        return PphTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PphTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPphTypes::route('/'),
            'create' => CreatePphType::route('/create'),
            'edit' => EditPphType::route('/{record}/edit'),
        ];
    }
}
