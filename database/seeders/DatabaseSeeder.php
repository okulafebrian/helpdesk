<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Location::factory()->count(5)->create();
        
        $this->call([
            UserSeeder::class,
            SlaPolicySeeder::class,
            // TicketSeeder::class,
        ]);
    }
}
