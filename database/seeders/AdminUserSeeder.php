<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'jridev2@gmail.com'], // cek kalau sudah ada jangan dobel
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('123123123'), // ganti sesuai kebutuhan
                'is_admin' => true,
            ]
        );
    }
}
