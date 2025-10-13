<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('positions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('election_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->unsignedInteger('quota')->default(1); // 1 pemenang (plural winner ≈ quota>1)
      $table->unsignedInteger('order')->default(0);
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('positions'); }
};
