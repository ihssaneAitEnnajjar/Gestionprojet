<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $guardName = 'web';

        // Check if the role already exists for the guard
        if (!Role::where('name', 'manager')->where('guard_name', $guardName)->exists()) {
            Role::create(['name' => 'manager', 'guard_name' => $guardName]);
        }

        // Check if the admin role already exists for the guard
        if (!Role::where('name', 'admin')->where('guard_name', $guardName)->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => $guardName]);
        }

        // Check if the user role already exists (no specific guard)
        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }

        // Your other role seeding code here...
    }}
