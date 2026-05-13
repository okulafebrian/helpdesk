<?php

namespace App\Models;

use App\Enums\SupportLevel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'support_level'])]
class Agent extends Model
{
    protected function casts(): array
    {
        return [
            'support_level' => SupportLevel::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
