<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_cotisation_id')->constrained('periodes_cotisation')->cascadeOnDelete();
            $table->decimal('montant_du', 12, 2);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->enum('statut', ['en_attente', 'partiel', 'valide', 'en_retard', 'exonere'])->default('en_attente');
            $table->text('motif_exoneration')->nullable();
            $table->unique(['utilisateur_id', 'periode_cotisation_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
