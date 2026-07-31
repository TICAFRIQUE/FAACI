<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->enum('nature', ['argent', 'materiel', 'autre'])->default('argent');
            $table->string('libelle', 255); // intitulé du don
            $table->decimal('montant', 12, 2)->nullable(); // uniquement si nature = argent
            $table->string('valeur_estimee', 100)->nullable(); // pour matériel ou autre
            $table->string('moyen_paiement', 50)->nullable(); // si argent
            $table->text('description')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'rejete', 'annule'])->default('en_attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dons');
    }
};
