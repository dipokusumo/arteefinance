<?php

namespace App\Filament\Resources\Taxpayers\Pages;

use App\Filament\Resources\Taxpayers\TaxpayerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxpayer extends CreateRecord
{
    protected static string $resource = TaxpayerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
