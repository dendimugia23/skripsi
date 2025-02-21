<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'), // Password di-hash
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Super Admin User',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('superadmin'), // Password di-hash
            'role' => 'super_admin',
        ]);
    }
}
