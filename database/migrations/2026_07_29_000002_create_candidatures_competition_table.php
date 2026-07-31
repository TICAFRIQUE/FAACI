<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures_competition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('utilisateur_id');
            $table->string('titre_projet', 200);
            $table->text('resume_projet');
            $table->enum('statut', ['en_attente', 'selectionnee', 'eliminee', 'gagnante'])->default('en_attente');
            $table->text('note_jury')->nullable();
            $table->timestamps();

            $table->unique(['competition_id', 'utilisateur_id'], 'cand_competition_unique');
            $table->foreign('competition_id')->references('id')->on('competitions')->cascadeOnDelete();
            $table->foreign('utilisateur_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures_competition');
    }
};
