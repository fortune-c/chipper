<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Chip;
use Illuminate\Database\Seeder;

class ChipSeeder extends Seeder
{
    public function run(): void
    {
        // Create a few sample users if they don't exist
        $users = User::count() < 3
                    ? collect([
                        User::create([
                            'name' => 'Alice Developer',
                            'email' => 'alice@example.com',
                            'password' => bcrypt('password'),
                        ]),
                        User::create([
                            'name' => 'Bob Builder',
                            'email' => 'bob@example.com',
                            'password' => bcrypt('password'),
                        ]),
                        User::create([
                            'name' => 'Charlie Coder',
                            'email' => 'charlie@example.com',
                            'password' => bcrypt('password'),
                        ]),
                    ])
                    : User::take(3)->get();

        // Sample chips
        $chips = [
            'Just discovered Laravel - where has this been all my life? 🚀',
            'Building something cool with Chipper today!',
            'Laravel\'s Eloquent ORM is pure magic ✨',
            'Deployed my first app with Laravel Cloud. So smooth!',
            'Who else is loving Blade components?',
            'Friday deploys with Laravel? No problem! 😎',
        ];

        // Create chips for random users
        foreach ($chips as $message) {
            $users->random()->chips()->create([
                'message' => $message,
                'created_at' => now()->subMinutes(rand(5, 1440)),
            ]);
        }
    }
}