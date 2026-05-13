<?php

namespace Database\Factories;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Location;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => null,
            'priority' => $this->faker->randomElement(TicketPriority::cases()),
            'category' => $this->faker->randomElement(TicketCategory::cases()),
            'location_id' => null,
            'requester_id' => null,
            'assignee_id' => null,
            'resolved_at' => null,
        ];
    }
}
