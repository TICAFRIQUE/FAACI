<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles and a default account for each role.
     */
    public function run(): void
    {
        foreach (['membre', 'admin', 'super_admin'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@faaci.org'],
            [
                'prenom' => 'Super',
                'nom' => 'Admin',
                'password' => 'password',
                'statut' => User::STATUT_ACTIF,
                'date_validation' => now(),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['super_admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@faaci.org'],
            [
                'prenom' => 'Admin',
                'nom' => 'FAACI',
                'password' => 'password',
                'statut' => User::STATUT_ACTIF,
                'date_validation' => now(),
                'email_verified_at' => now(),
                'valide_par' => $superAdmin->id,
            ]
        );
        $admin->syncRoles(['admin']);

        $membre = User::firstOrCreate(
            ['email' => 'membre@faaci.org'],
            [
                'prenom' => 'Membre',
                'nom' => 'Test',
                'password' => 'password',
                'statut' => User::STATUT_ACTIF,
                'date_validation' => now(),
                'email_verified_at' => now(),
                'valide_par' => $admin->id,
            ]
        );
        $membre->syncRoles(['membre']);
    }
}
