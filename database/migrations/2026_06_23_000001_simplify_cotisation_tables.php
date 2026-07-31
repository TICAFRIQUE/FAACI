<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter date_debut et date_fin (nullable = infini) sur types_cotisation
        Schema::table('types_cotisation', function (Blueprint $table) {
            $table->date('date_debut')->nullable()->after('description');
            $table->date('date_fin')->nullable()->after('date_debut');
        });

        // Ajouter numero_transaction et mois_couverts sur paiements_cotisation
        Schema::table('paiements_cotisation', function (Blueprint $table) {
            $table->string('numero_transaction', 100)->nullable()->after('moyen_paiement');
            $table->json('mois_couverts')->nullable()->after('numero_transaction');
        });
    }

    public function down(): void
    {
        Schema::table('types_cotisation', function (Blueprint $table) {
            $table->dropColumn(['date_debut', 'date_fin']);
        });
        Schema::table('paiements_cotisation', function (Blueprint $table) {
            $table->dropColumn(['numero_transaction', 'mois_couverts']);
        });
    }
};
