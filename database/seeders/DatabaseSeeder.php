<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => 'Manager',
            'email' => 'Manager@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);
        foreach (['ali', 'sara', 'omar'] as $name){
            User::factory()->create([
                'name' => ucfirst($name),
                'email' => "{$name}@example.com",
                'password' => bcrypt('password'),
                'role' => 'employee',
            ]);
        }
    }
}
