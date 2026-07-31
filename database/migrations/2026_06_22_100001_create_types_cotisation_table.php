<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_cotisation', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->enum('frequence', ['journaliere', 'hebdomadaire', 'mensuelle', 'semestrielle', 'annuelle', 'autre']);
            $table->decimal('montant_standard', 12, 2);
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_cotisation');
    }
};
