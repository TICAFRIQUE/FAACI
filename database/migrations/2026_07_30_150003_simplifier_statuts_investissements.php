<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Passer en VARCHAR pour lever la contrainte d'enum
        DB::statement("ALTER TABLE investissements MODIFY COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'promesse'");

        // 2. Migrer les anciennes valeurs
        DB::statement("UPDATE investissements SET statut = 'paye'     WHERE statut IN ('paid', 'confirmed')");
        DB::statement("UPDATE investissements SET statut = 'partiel'  WHERE statut = 'partial'");
        DB::statement("UPDATE investissements SET statut = 'promesse' WHERE statut IN ('pending', 'cancelled')");

        // 3. Appliquer le nouvel enum — 3 statuts seulement
        DB::statement("ALTER TABLE investissements MODIFY COLUMN statut ENUM('promesse','partiel','paye') NOT NULL DEFAULT 'promesse'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE investissements MODIFY COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'pending'");
        DB::statement("UPDATE investissements SET statut = 'pending' WHERE statut = 'promesse'");
        DB::statement("UPDATE investissements SET statut = 'partial' WHERE statut = 'partiel'");
        DB::statement("UPDATE investissements SET statut = 'paid'    WHERE statut = 'paye'");
        DB::statement("ALTER TABLE investissements MODIFY COLUMN statut ENUM('pending','confirmed','paid','partial','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
