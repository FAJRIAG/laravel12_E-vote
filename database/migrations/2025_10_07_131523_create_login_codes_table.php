<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('login_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 24)->unique();       // contoh: ABCD-1234-EFGH
            $table->string('label')->nullable();        // keterangan bebas (kelas A, panitia, dll)
            $table->foreignId('created_by')->constrained('users'); // admin pembuat
            $table->foreignId('user_id')->nullable()->constrained('users'); // opsional: bila code ditugaskan ke user tertentu
            $table->unsignedInteger('max_uses')->default(1);       // 1 = sekali pakai; bisa dinaikkan
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();              // terakhir dipakai (untuk 1x pakai)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_codes');
    }
};
