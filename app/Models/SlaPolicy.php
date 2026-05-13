<?php

namespace App\Models;

use App\Enums\TicketPriority;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['priority', 'response_hrs', 'resolution_hrs'])]
class SlaPolicy extends Model
{
    protected function casts(): array
    {
        return [
            'priority' => TicketPriority::class,
        ];
    }
}
