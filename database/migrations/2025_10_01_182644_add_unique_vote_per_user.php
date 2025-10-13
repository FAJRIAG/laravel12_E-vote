<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('votes', function (Blueprint $table) {
            // Unik global per user: 1 baris vote saja sepanjang masa
            $table->unique('user_id', 'votes_unique_per_user');
        });
    }
    public function down(): void {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_unique_per_user');
        });
    }
};
