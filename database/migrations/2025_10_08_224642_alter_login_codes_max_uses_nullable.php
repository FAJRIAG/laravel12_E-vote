<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Pastikan kolom jadi nullable (boleh null)
        Schema::table('login_codes', function (Blueprint $table) {
            // Untuk MySQL, ubah kolom existing menjadi nullable
            DB::statement('ALTER TABLE login_codes MODIFY max_uses INT UNSIGNED NULL');
        });
    }

    public function down(): void
    {
        // Kembalikan jadi NOT NULL dengan default 1 (atau sesuai kebutuhan)
        Schema::table('login_codes', function (Blueprint $table) {
            DB::statement('ALTER TABLE login_codes MODIFY max_uses INT UNSIGNED NOT NULL DEFAULT 1');
        });
    }
};
