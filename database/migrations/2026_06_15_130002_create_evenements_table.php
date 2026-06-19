<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['reunion', 'pitch', 'webinaire', 'networking', 'ag']);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->string('lien_visio')->nullable();
            $table->enum('statut', ['brouillon', 'publie'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
