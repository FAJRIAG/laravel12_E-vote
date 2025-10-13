<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom-kolom baru bila belum ada
        if (!Schema::hasColumn('login_codes', 'is_active')
            || !Schema::hasColumn('login_codes', 'is_one_time')
            || !Schema::hasColumn('login_codes', 'used_at')
            || !Schema::hasColumn('login_codes', 'last_used_at')
            || !Schema::hasColumn('login_codes', 'expires_at')
            || !Schema::hasColumn('login_codes', 'user_id')) {

            Schema::table('login_codes', function (Blueprint $table) {
                if (!Schema::hasColumn('login_codes', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('code');
                }
                if (!Schema::hasColumn('login_codes', 'is_one_time')) {
                    $table->boolean('is_one_time')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('login_codes', 'used_at')) {
                    $table->timestamp('used_at')->nullable()->after('is_one_time');
                }
                if (!Schema::hasColumn('login_codes', 'last_used_at')) {
                    $table->timestamp('last_used_at')->nullable()->after('used_at');
                }
                if (!Schema::hasColumn('login_codes', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->after('last_used_at');
                }
                if (!Schema::hasColumn('login_codes', 'user_id')) {
                    $table->foreignId('user_id')->nullable()
                        ->constrained('users')->nullOnDelete()->after('expires_at');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (Schema::hasColumn('login_codes', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('login_codes', 'expires_at')) {
                $table->dropColumn('expires_at');
            }
            if (Schema::hasColumn('login_codes', 'last_used_at')) {
                $table->dropColumn('last_used_at');
            }
            if (Schema::hasColumn('login_codes', 'used_at')) {
                $table->dropColumn('used_at');
            }
            if (Schema::hasColumn('login_codes', 'is_one_time')) {
                $table->dropColumn('is_one_time');
            }
            if (Schema::hasColumn('login_codes', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
