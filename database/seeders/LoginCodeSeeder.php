<?php

namespace Database\Seeders;

use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LoginCodeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        if (!$admin) return;

        LoginCode::create([
            'code'       => 'ABCD-1234-EFGH',
            'label'      => 'Testing',
            'created_by' => $admin->id,
            'max_uses'   => 5,
            'is_active'  => true,
        ]);
    }
}
