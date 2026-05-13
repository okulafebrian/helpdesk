<?php

namespace Database\Seeders;

use App\Enums\TicketPriority;
use App\Models\SlaPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlaPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SlaPolicy::create([
            'priority' => TicketPriority::LOW,
            'response_hrs' => 4,
            'resolution_hrs' => 72,
        ]);

        SlaPolicy::create([
            'priority' => TicketPriority::MEDIUM,
            'response_hrs' => 3,
            'resolution_hrs' => 24,
        ]);

        SlaPolicy::create([
            'priority' => TicketPriority::HIGH,
            'response_hrs' => 2,
            'resolution_hrs' => 8,
        ]);

        SlaPolicy::create([
            'priority' => TicketPriority::CRITICAL,
            'response_hrs' => 1,
            'resolution_hrs' => 4,
        ]);
    }
}
