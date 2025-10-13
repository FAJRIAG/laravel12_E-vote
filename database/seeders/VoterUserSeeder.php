<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VoterUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'pemilih@example.com'],
            [
                'name'     => 'User Pemilih',
                'password' => Hash::make('123123123'),
                'is_admin' => false,
            ]
        );
    }
}
