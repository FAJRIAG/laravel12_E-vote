<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom email ke tabel login_codes
     */
    public function up(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('login_codes', 'email')) {
                $table->string('email', 191)->nullable()->index()->after('code');
            }
        });
    }

    /**
     * Hapus kolom email saat rollback
     */
    public function down(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (Schema::hasColumn('login_codes', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
