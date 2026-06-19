<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres_emploi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'freelance', 'alternance']);
            $table->string('localisation')->nullable();
            $table->string('salaire')->nullable();
            $table->text('competences_requises')->nullable();
            $table->string('lien_externe')->nullable();
            $table->date('date_expiration')->nullable();
            $table->enum('statut', ['brouillon', 'en_attente', 'active', 'expiree', 'rejetee'])->default('en_attente');
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres_emploi');
    }
};
