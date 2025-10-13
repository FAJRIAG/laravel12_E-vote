<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Admin agar ada akun admin default
        $this->call(AdminUserSeeder::class);
        $this->call(VoterUserSeeder::class);
        $this->call(LoginCodeSeeder::class);


        // Kalau masih mau generate user dummy dengan factory
        // uncomment baris di bawah:
        // \App\Models\User::factory(10)->create();
    }
}
