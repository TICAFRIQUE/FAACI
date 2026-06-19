<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('montant_promis', 12, 2);
            $table->decimal('montant_paye', 12, 2)->default(0);

            $table->enum('statut', ['pending', 'confirmed', 'paid', 'partial', 'cancelled'])->default('pending');

            // Déclaration de paiement par le membre
            $table->text('note')->nullable();
            $table->timestamp('date_declaration_paiement')->nullable();

            // Validation admin
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->text('motif_rejet')->nullable();

            // Moyen de paiement déclaré
            $table->enum('moyen_paiement', ['cash', 'orange_money', 'wave', 'virement', 'autre'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
