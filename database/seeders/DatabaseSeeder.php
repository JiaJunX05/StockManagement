<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::withoutEvents(function() {
            User::updateorCreate
            (
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('123qwe'),
                    'role' => 'admin',
                ]
            );

            User::updateorCreate
            (
                ['email' => 'staff@example.com'],
                [
                    'name' => 'Staff',
                    'password' => Hash::make('123qwe'),
                    'role' => 'staff',
                ]
            );
        });
    }
}
