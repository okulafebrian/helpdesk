<?php

namespace App\Models;

use App\Enums\RolesEnum;
use App\Enums\SlaStatus;
use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['number', 'title', 'description', 'status', 'priority', 'category', 'location_id', 'requester_id', 'assignee_id', 'first_response_at', 'resolved_at', 'response_due_at', 'resolution_due_at', 'response_sla_status', 'resolution_sla_status'])]
class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'priority' => TicketPriority::class,
            'category' => TicketCategory::class,
            'first_response_at' => 'datetime',
            'resolved_at' => 'datetime',
            'response_due_at' => 'datetime',
            'resolution_due_at' => 'datetime',
            'response_sla_status' => SlaStatus::class,
            'resolution_sla_status' => SlaStatus::class,
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', RolesEnum::AGENT);
            });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
