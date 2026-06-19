<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('declarations_paiement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_id')->constrained('contributions')->cascadeOnDelete();
            $table->decimal('montant', 12, 2);
            $table->enum('moyen_paiement', ['cash', 'orange_money', 'wave', 'virement', 'autre']);
            $table->text('note')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declarations_paiement');
    }
};
