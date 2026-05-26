<?php

namespace App\Filament\Resources\Pics\Pages;

use App\Filament\Resources\Pics\PicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPic extends EditRecord
{
    protected static string $resource = PicResource::class;

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
