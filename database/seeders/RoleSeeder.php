<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Rol; // Legacy role model

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Spatie Roles
        $roles = ['Admin', 'Productor', 'Agronomo'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            // 2. Create Legacy Roles (to match the 'rol' table expected by some models)
            Rol::firstOrCreate([
                'nombre' => $roleName,
                'descripcion' => "Rol de sistema para {$roleName}"
            ]);
        }
    }
}
