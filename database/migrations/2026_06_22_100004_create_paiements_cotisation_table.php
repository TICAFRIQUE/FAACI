<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_cotisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('type_cotisation_id')->constrained('types_cotisation')->cascadeOnDelete();
            $table->decimal('montant', 12, 2);
            $table->string('moyen_paiement', 50)->nullable();
            $table->text('note')->nullable();
            $table->foreignId('saisi_par')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_cotisation');
    }
};
