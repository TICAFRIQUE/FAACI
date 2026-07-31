<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->date('date_limite_candidature')->nullable();
            $table->date('date_pitch')->nullable();
            $table->enum('statut', ['brouillon', 'ouverte', 'cloturee', 'terminee'])->default('brouillon');
            $table->unsignedBigInteger('cree_par')->nullable();
            $table->timestamps();

            $table->foreign('cree_par')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
