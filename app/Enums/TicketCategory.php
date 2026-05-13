<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketCategory: string implements HasLabel
{
    case GENERAL = 'general';
    case BILLING = 'billing';
    case NETWORK = 'network';
    case HARDWARE = 'hardware';
    case SOFTWARE = 'software';

    public function getLabel(): string
    {
        return match ($this) {
            self::GENERAL => 'General',
            self::BILLING => 'Billing',
            self::NETWORK => 'Network',
            self::HARDWARE => 'Hardware',
            self::SOFTWARE => 'Software',
        };
    }
}
