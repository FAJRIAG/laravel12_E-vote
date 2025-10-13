<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('candidates', function (Blueprint $table) {
      $table->id();
      $table->foreignId('election_id')->constrained()->cascadeOnDelete();
      $table->foreignId('position_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->text('vision')->nullable();
      $table->text('mission')->nullable();
      $table->string('photo_path')->nullable();
      $table->unsignedInteger('order')->default(0);
      $table->timestamps();
      $table->index(['election_id','position_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('candidates'); }
};
