<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->boolean('est_public')->default(false)->after('statut');
            $table->unsignedInteger('capacite_max')->nullable()->after('est_public');
            $table->foreignId('organisateur_id')->nullable()->constrained('users')->nullOnDelete()->after('capacite_max');
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropForeign(['organisateur_id']);
            $table->dropColumn(['est_public', 'capacite_max', 'organisateur_id']);
        });
    }
};
