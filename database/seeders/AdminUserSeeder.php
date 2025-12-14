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

    }
}
