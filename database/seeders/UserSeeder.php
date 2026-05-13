<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'end_user']);

        User::factory()
            ->superAdmin()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        User::factory()->agent()->count(5)->create();
        User::factory()->endUser()->count(8)->create();
    }
}
