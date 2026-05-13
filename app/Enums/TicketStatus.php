<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TicketStatus: string implements HasLabel, HasColor
{
    case NEW = 'new';
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::NEW => 'New',
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::PENDING => 'Pending',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'info',
            self::OPEN => 'danger',
            self::IN_PROGRESS => 'warning',
            self::PENDING => 'warning',
            self::RESOLVED => 'success',
            self::CLOSED => 'gray',
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::NEW => $target == self::OPEN,
            self::OPEN => in_array($target, [self::IN_PROGRESS, self::PENDING, self::RESOLVED]),
            self::IN_PROGRESS => in_array($target, [self::PENDING, self::RESOLVED]),
            self::PENDING => in_array($target, [self::IN_PROGRESS, self::RESOLVED]),
            self::RESOLVED => $target == self::CLOSED,
            self::CLOSED => false,
        }; 
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::CLOSED]);
    }

    public static function fromAllowed(self $current): array
    {
        return collect(self::cases())
            ->filter(fn (self $status) => $status === $current || $current->canTransitionTo($status))
            ->mapWithKeys(fn (self $status) => [$status->value => $status->getLabel()])
            ->toArray();
    }
}
