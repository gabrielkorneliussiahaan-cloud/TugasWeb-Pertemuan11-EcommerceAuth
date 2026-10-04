<?php

// ====================================================================
// database/seeders/UserSeeder.php
// REQUIREMENT 6 & 8: 3 akun demo, 1 per role, supaya gampang dites
// (termasuk REQUIREMENT 8: testing lewat 2 browser/incognito berbeda).
// Password semua akun demo: "password"
// ====================================================================

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tugasweb.test'],
            ['name' => 'Admin', 'role' => 'admin', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'editor@tugasweb.test'],
            ['name' => 'Editor', 'role' => 'editor', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'user@tugasweb.test'],
            ['name' => 'User Biasa', 'role' => 'user', 'password' => Hash::make('password')]
        );

        // Beberapa user tambahan buat data review/order biar lebih realistis.
        User::factory(5)->create(['role' => 'user']);
    }
}
