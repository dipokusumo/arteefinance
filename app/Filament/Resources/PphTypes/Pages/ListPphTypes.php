<?php

namespace App\Filament\Resources\PphTypes\Pages;

use App\Filament\Resources\PphTypes\PphTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPphTypes extends ListRecords
{
    protected static string $resource = PphTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
