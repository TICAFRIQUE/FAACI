<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_periode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_cotisation_id')->constrained('paiements_cotisation')->cascadeOnDelete();
            $table->foreignId('periode_cotisation_id')->constrained('periodes_cotisation')->cascadeOnDelete();
            $table->decimal('montant_attribue', 12, 2);
            $table->unique(['paiement_cotisation_id', 'periode_cotisation_id'], 'pp_paiement_periode_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_periode');
    }
};
