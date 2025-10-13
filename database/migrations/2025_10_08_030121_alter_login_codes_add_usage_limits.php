<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('login_codes', 'max_uses')) {
                $table->unsignedInteger('max_uses')->nullable()->after('is_one_time'); // null = tak terbatas
            }
            if (!Schema::hasColumn('login_codes', 'uses_count')) {
                $table->unsignedInteger('uses_count')->default(0)->after('max_uses');
            }
        });
    }

    public function down(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            if (Schema::hasColumn('login_codes', 'uses_count')) {
                $table->dropColumn('uses_count');
            }
            if (Schema::hasColumn('login_codes', 'max_uses')) {
                $table->dropColumn('max_uses');
            }
        });
    }
};
