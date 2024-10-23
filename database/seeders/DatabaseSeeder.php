<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);
        \App\Models\Ticket::create([
            'name' => 'VIP Ticket',
            'image' => 'vip.jpg',
            'description' => 'Access to all areas.',
            'price' => 150.00,
            'total' => 100,
            'available' => 100,
            'status' => true,
        ]);

        \App\Models\Ticket::create([
            'name' => 'General Admission',
            'image' => 'general.jpg',
            'description' => 'Standard access.',
            'price' => 50.00,
            'total' => 200,
            'available' => 200,
            'status' => true,
        ]);
    }
}
