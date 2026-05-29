<?php

namespace App\Filament\Resources\PphTypes\Pages;

use App\Filament\Resources\PphTypes\PphTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPphType extends EditRecord
{
    protected static string $resource = PphTypeResource::class;

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
