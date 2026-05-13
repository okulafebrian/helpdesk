<?php

namespace App\Models;

use App\Enums\LocationType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'location_type'])]
class Location extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'location_type' => LocationType::class,
        ];
    }
}
