<?php

namespace App\Helpers;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class BulkStatusAction
{
    public static function makeBulkStatusAction(
        string $name,
        string $column,
        mixed $value,
        string $label,
        string $icon,
        string $color
    ): Action {
        return Action::make($name)
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->accessSelectedRecords()
            ->requiresConfirmation()
            ->action(function (Collection $records) use ($column, $value, $label) {
                $count = $records->count();

                $records->each->update([
                    $column => $value,
                ]);

                Notification::make()
                    ->success()
                    ->title($label)
                    ->body("{$count} data berhasil diperbarui.")
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }
}
