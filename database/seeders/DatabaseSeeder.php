<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@flowzone.com',
            'password' => Hash::make('admin123'),
            'rol'      => 'admin',
            'estado'   => 'activo',
        ]);

        // Usuario de prueba
        User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'juan@example.com',
            'password' => Hash::make('admin123'),
            'rol'      => 'usuario',
            'estado'   => 'activo',
        ]);

        // Empresa de prueba
        $empresaUser = User::create([
            'name'     => 'Empresa Demo',
            'email'    => 'empresa@example.com',
            'password' => Hash::make('admin123'),
            'rol'      => 'empresa',
            'estado'   => 'activo',
        ]);

        Empresa::create([
            'usuario_id' => $empresaUser->id,
            'nombre'     => 'Empresa Demo S.A.S',
            'telefono'   => null,
            'direccion'  => null,
            'aprobado'   => true,
        ]);

        // Hoteles, lugares, eventos y gastronomía se gestionan desde el panel admin.
    }
}
