<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create s.merhi@ids.com.lb user
        User::updateOrCreate(
            ['email' => 's.merhi@ids.com.lb'],
            [
                'name' => 'S. Merhi',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
