<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('telephone');
            $table->string('promotion_aiesec', 10)->nullable()->after('bio');
            $table->string('secteur', 150)->nullable()->after('promotion_aiesec');
            $table->string('ville', 100)->nullable()->after('secteur');
            $table->json('competences')->nullable()->after('ville');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'promotion_aiesec', 'secteur', 'ville', 'competences']);
        });
    }
};
