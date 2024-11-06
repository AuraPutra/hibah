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

        User::create([
            'name' => 'Aura',
            'email' => 'aura22ti@mahasiswa.pcr.ac.id',
            'is_admin' => 1,
        ]);

        $this->call(SettingsSeeder::class);
    }
}
