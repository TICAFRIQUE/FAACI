<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE contenus_sections MODIFY COLUMN `type` ENUM('texte','textarea','richtext','nombre','image') NOT NULL DEFAULT 'texte'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE contenus_sections MODIFY COLUMN `type` ENUM('texte','textarea','richtext','nombre') NOT NULL DEFAULT 'texte'");
    }
};
