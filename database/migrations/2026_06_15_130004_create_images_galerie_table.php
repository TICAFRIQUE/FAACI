<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images_galerie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_galerie_id')->constrained('albums_galerie')->cascadeOnDelete();
            $table->string('legende')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images_galerie');
    }
};
