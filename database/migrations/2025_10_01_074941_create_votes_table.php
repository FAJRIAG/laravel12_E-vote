<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('votes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('election_id')->constrained()->cascadeOnDelete();
      $table->foreignId('position_id')->constrained()->cascadeOnDelete();
      $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['user_id','election_id','position_id']); // 1 user = 1 suara per posisi
      $table->index(['election_id','position_id','candidate_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('votes'); }
};
