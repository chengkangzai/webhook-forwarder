<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InstanceStatus: string implements HasColor, HasLabel
{
    case ACTIVE = 'active';
    case DELETED = 'deleted';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => Color::Green,
            self::DELETED => Color::Red,
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::DELETED => 'Deleted',
        };
    }
}
