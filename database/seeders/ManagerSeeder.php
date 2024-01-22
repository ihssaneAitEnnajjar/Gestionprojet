<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Find the user by email
        $user = User::where('email', 'manager@gmail.com')->first();

        if ($user) {
            // If the user already exists, update their role to 'manager'
            $user->assignRole('manager');
        } else {
        User::create([
            'name' => 'manager',
            'email' => 'manager@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole('manager');
    }}
    }

