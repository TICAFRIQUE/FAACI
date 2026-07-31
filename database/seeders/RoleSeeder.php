<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['membre', 'admin', 'super_admin'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $superAdmin = User::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'superadmin@faaci.org')],
            [
                'prenom'             => 'Super',
                'nom'                => 'Admin',
                'password'           => Hash::make(env('SUPER_ADMIN_PASSWORD', 'Faaci@2026!')),
                'statut'             => User::STATUT_ACTIF,
                'date_validation'    => now(),
                'email_verified_at'  => now(),
            ]
        );
        $superAdmin->syncRoles(['super_admin']);
    }
}
