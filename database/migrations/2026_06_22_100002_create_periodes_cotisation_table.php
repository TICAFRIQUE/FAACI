<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodes_cotisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_cotisation_id')->constrained('types_cotisation')->cascadeOnDelete();
            $table->string('libelle', 200);
            $table->date('date_debut');
            $table->date('date_fin_paiement');
            $table->decimal('montant_standard', 12, 2);
            $table->enum('statut', ['ouverte', 'fermee', 'archivee'])->default('ouverte');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodes_cotisation');
    }
};
