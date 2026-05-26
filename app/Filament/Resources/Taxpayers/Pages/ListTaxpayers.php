<?php

namespace App\Filament\Resources\Taxpayers\Pages;

use App\Filament\Resources\Taxpayers\TaxpayerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaxpayers extends ListRecords
{
    protected static string $resource = TaxpayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
