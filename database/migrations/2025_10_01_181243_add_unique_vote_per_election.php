<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Pastikan tidak ada index duplikat sebelumnya
            // kalau sudah ada, hapus manual lewat phpMyAdmin atau migration lain
            $table->unique(['election_id','user_id'], 'votes_unique_per_election');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_unique_per_election');
        });
    }
};
