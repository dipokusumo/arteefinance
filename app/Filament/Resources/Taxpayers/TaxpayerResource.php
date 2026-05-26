<?php

namespace App\Filament\Resources\Taxpayers;

use App\Filament\Resources\Taxpayers\Pages\CreateTaxpayer;
use App\Filament\Resources\Taxpayers\Pages\EditTaxpayer;
use App\Filament\Resources\Taxpayers\Pages\ListTaxpayers;
use App\Filament\Resources\Taxpayers\Schemas\TaxpayerForm;
use App\Filament\Resources\Taxpayers\Tables\TaxpayersTable;
use App\Models\Taxpayer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxpayerResource extends Resource
{
    protected static ?string $model = Taxpayer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 5000;
    
    protected static ?string $recordTitleAttribute = 'Taxpayer';

    public static function form(Schema $schema): Schema
    {
        return TaxpayerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxpayersTable::configure($table);
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
            'index' => ListTaxpayers::route('/'),
            'create' => CreateTaxpayer::route('/create'),
            'edit' => EditTaxpayer::route('/{record}/edit'),
        ];
    }
}
