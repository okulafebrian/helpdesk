<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TicketPriority: string implements HasLabel, HasColor
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LOW => 'success',
            self::MEDIUM => 'info',
            self::HIGH => 'warning',
            self::CRITICAL => 'danger',
        };
    }
}
