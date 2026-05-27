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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Orang Tua Demo',
            'username' => 'ortu',
            'email' => 'ortu@example.com',
            'role' => User::ROLE_PARENT,
        ]);

        User::factory()->create([
            'name' => 'Panitia PPDB',
            'username' => 'panitia',
            'email' => 'panitia@example.com',
            'role' => User::ROLE_COMMITTEE,
        ]);

        User::factory()->create([
            'name' => 'Kepala Sekolah',
            'username' => 'kepsek',
            'email' => 'kepsek@example.com',
            'role' => User::ROLE_PRINCIPAL,
        ]);
    }
}
