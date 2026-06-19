<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_emploi_id')->constrained('offres_emploi')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->text('lettre_motivation')->nullable();
            $table->enum('statut', ['soumise', 'en_cours', 'acceptee', 'rejetee'])->default('soumise');
            $table->text('note_recruteur')->nullable();
            $table->timestamps();

            $table->unique(['offre_emploi_id', 'utilisateur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
