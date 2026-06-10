<?php

namespace App\Filament\Schemas\Components;

use Filament\Schemas\Components\Component;
use Closure;
use Filament\Support\RawJs;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns;
use Filament\Forms\Components\Concerns\HasStep;
use Filament\Tables\Columns\Contracts\Editable;
use Filament\Forms\Components\Concerns\HasInputMode;
use Filament\Tables\Columns\Concerns\CanFormatState;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;

class InlineEditColumn extends Column implements Editable
{
    use Concerns\CanBeValidated;
    use Concerns\CanUpdateState;
    use HasExtraInputAttributes;
    use CanFormatState;
    use HasInputMode;
    use HasStep;
    protected string $view = 'filament.schemas.components.inline-edit-column';

    protected string|Closure|null $type = 'text';

    protected string|Closure|null $field = null;

    public static function make(string|null $field = null): static
    {
        $instance = parent::make($field);

        if ($field) {
            $instance->field($field);
        }

        return $instance;
    }

    public function field(string|Closure|null $field): static
    {
        $this->field = $field;
        return $this;
    }

    public function getField(): ?string
    {
        return $this->evaluate($this->field);
    }

    public function type(string|Closure|null $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->evaluate($this->type) ?? 'text';
    }

    public function validate(mixed $input): void
    {
        // Inline edit columns do not define validation here.
    }
}