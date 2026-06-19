<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();

            $table->string('titre', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->text('description_courte')->nullable();

            // Financement
            $table->enum('type_financement', ['fixe', 'ouvert'])->default('fixe');
            $table->decimal('montant_cible', 12, 2)->nullable();
            $table->decimal('montant_collecte', 12, 2)->default(0);

            // Cycle de vie
            $table->enum('statut', [
                'brouillon',
                'en_attente',
                'valide',
                'en_financement',
                'finance',
                'en_cours',
                'termine',
                'rejete',
            ])->default('brouillon');

            $table->text('motif_rejet')->nullable();

            // Validation admin
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();

            // Dates projet
            $table->date('date_debut')->nullable();
            $table->date('date_fin_financement')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
