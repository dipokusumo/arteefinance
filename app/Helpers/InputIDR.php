<?php

use Filament\Forms\Components\TextInput;

if (!function_exists('InputIDR')) {
    function InputIDR(string $name, string $label = 'Harga'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->debounce(500)
            ->live()

            // FORMAT dari DB → UI
            ->formatStateUsing(
                fn($state) =>
                $state === null
                ? null
                : number_format((float) $state, 0, ',', '.')
            )

            // SAAT USER NGETIK
            ->afterStateUpdated(function ($state, callable $set) use ($name) {
                if ($state === null || $state === '') {
                    $set($name, null);
                    return;
                }

                // cek minus di depan
                $isNegative = str_starts_with(trim($state), '-');

                // buang semua kecuali angka
                $numeric = preg_replace('/[^0-9]/', '', $state);

                if ($numeric === '') {
                    $set($name, null);
                    return;
                }

                $formatted = number_format((float) $numeric, 0, ',', '.');

                $set(
                    $name,
                    $isNegative ? '-' . $formatted : $formatted
                );
            })

            // UI → DB
            ->dehydrateStateUsing(
                fn($state) =>
                ($state === null || $state === '')
                ? null
                : (float) (
                    str_starts_with(trim($state), '-')
                    ? '-' . preg_replace('/[^0-9]/', '', $state)
                    : preg_replace('/[^0-9]/', '', $state)
                )
            )

            ->prefix('IDR');
    }
}