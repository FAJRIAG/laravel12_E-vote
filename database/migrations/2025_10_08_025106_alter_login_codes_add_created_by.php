<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('login_codes', 'created_by')) {
            Schema::table('login_codes', function (Blueprint $table) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (Schema::hasColumn('login_codes', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
        });
    }
};
