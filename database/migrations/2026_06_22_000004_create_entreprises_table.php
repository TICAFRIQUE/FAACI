<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
            $table->string('nom', 200);
            $table->string('secteur', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('localisation', 200)->nullable(); // ville / pays
            $table->string('site_web', 255)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email_contact', 255)->nullable();
            $table->string('annee_creation', 4)->nullable();
            $table->enum('statut', ['en_attente', 'actif', 'rejete', 'inactif'])->default('en_attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
