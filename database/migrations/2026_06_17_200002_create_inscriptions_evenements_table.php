<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained('evenements')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->enum('statut', ['inscrit', 'confirme', 'annule'])->default('inscrit');
            $table->timestamps();

            $table->unique(['evenement_id', 'utilisateur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_evenements');
    }
};
