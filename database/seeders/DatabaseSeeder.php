<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Contenu site vitrine
        $this->call(ContenuSectionSeeder::class);
        $this->call(ParametreSiteSeeder::class);
        $this->call(ActiviteSeeder::class);
        $this->call(ValeurSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(EquipeSeeder::class);
    }
}
