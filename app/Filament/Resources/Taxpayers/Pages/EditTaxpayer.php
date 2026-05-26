<?php

namespace App\Filament\Resources\Taxpayers\Pages;

use App\Filament\Resources\Taxpayers\TaxpayerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaxpayer extends EditRecord
{
    protected static string $resource = TaxpayerResource::class;

    public function getTitle(): string
    {
        return 'Edit ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
