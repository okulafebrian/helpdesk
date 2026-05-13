<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Models\Comment;
use App\Models\Location;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::pluck('id');
        $users = User::pluck('id');

        Ticket::factory()
            ->count(12)
            ->state(function () use ($locations, $users) {
                $status = collect(TicketStatus::cases())->random();

                return [
                    'status' => $status,
                    'location_id' => $locations->random(),
                    'requester_id' => $users->random(),
                    'assignee_id' => $status != TicketStatus::NEW ? $users->random(): null,
                    'responded_at' => $status != TicketStatus::NEW ? now()->subHours(2) : null,
                    'resolved_at' => in_array($status, [
                        TicketStatus::RESOLVED,
                        TicketStatus::CLOSED,
                    ]) ? now() : null,
                    'response_due_at' => $status != TicketStatus::NEW ? now()->subHour() : null,
                    'resolution_due_at' => $status != TicketStatus::NEW ? now()->addHour() : null
                ];
            })
            ->has(
                Comment::factory()
                    ->count(5)
                    ->state(function () use ($users) {
                        return [
                            'user_id' => $users->random()
                        ];
                    })
            )
            ->create();
    }
}