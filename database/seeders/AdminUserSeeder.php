<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Usuario::firstOrCreate(
            ['email' => 'admin@agronexus.com'],
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'nombreusuario' => 'admin',
                'telefono' => '123456789',
                'passwordhash' => Hash::make('password'), // Default password
                'activo' => true,
                'fecharegistro' => now(),
                'fechamodificacion' => now(),
            ]
        );

        // Assign Spatie Role
        $admin->assignRole('Admin');

        // Assign Legacy Role (via pivot table if necessary, but Spatie should handle permissions)
        // Check if legacy relationship needs manual population?
        // Usuario model has 'roles' via Spatie, but also 'rols' via legacy?
        // The migration created 'usuariorol'. Let's populate it just in case.
        $rolAdmin = \App\Models\Rol::where('nombre', 'Admin')->first();
        if ($rolAdmin) {
            \Illuminate\Support\Facades\DB::table('usuariorol')->updateOrInsert(
                ['usuarioid' => $admin->usuarioid, 'rolid' => $rolAdmin->rolid]
            );
        }
    }
}
