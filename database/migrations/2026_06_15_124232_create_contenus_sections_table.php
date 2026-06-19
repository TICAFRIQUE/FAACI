<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenus_sections', function (Blueprint $table) {
            $table->id();
            $table->string('groupe')->index();
            $table->string('cle')->unique();
            $table->string('libelle');
            $table->enum('type', ['texte', 'textarea', 'richtext', 'nombre'])->default('texte');
            $table->longText('valeur')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenus_sections');
    }
};
