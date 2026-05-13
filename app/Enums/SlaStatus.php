<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum SlaStatus: string implements HasLabel, HasColor
{
    case RUNNING = 'running';
    case MET = 'met';
    case BREACHED = 'breached';
    
    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::RUNNING => 'Running',
            self::MET => 'Met',
            self::BREACHED => 'Breached',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::RUNNING => 'success',
            self::MET => 'gray',
            self::BREACHED => 'danger',
        };
    }
}
