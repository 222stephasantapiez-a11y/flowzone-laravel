<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Lugar;
use App\Models\Hotel;
use App\Models\Evento;
use App\Models\Gastronomia;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@flowzone.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'estado' => 'activo'
        ]);

        $usuario = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('admin123'),
            'rol' => 'usuario',
            'estado' => 'activo'
        ]);

        $empresaUser = User::create([
            'name' => 'Hotel El Paraíso S.A.S',
            'email' => 'empresa@example.com',
            'password' => Hash::make('admin123'),
            'rol' => 'empresa',
            'estado' => 'activo'
        ]);

        Empresa::create([
            'usuario_id' => $empresaUser->id,
            'nombre' => 'Hotel El Paraíso S.A.S',
            'telefono' => '3201234567',
            'direccion' => 'Km 2 Vía Ortega-Chaparral',
            'aprobado' => 1
        ]);

        Lugar::insert([
            [
                'nombre' => 'Cascada La Chorrera',
                'descripcion' => 'Hermosa cascada natural...',
                'ubicacion' => 'Vereda El Bosque, Ortega',
                'latitud' => 3.8234567,
                'longitud' => -75.2345678,
                'categoria' => 'Naturaleza',
                'imagen' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9',
                'precio_entrada' => 5000,
                'horario' => '8:00 AM - 5:00 PM'
            ],
            [
                'nombre' => 'Mirador El Cielo',
                'descripcion' => 'Vista espectacular...',
                'ubicacion' => 'Alto de La Cruz, Ortega',
                'latitud' => 3.8345678,
                'longitud' => -75.2456789,
                'categoria' => 'Mirador',
                'imagen' => '',
                'precio_entrada' => 0,
                'horario' => '24 horas'
            ]
        ]);

        Hotel::insert([
            [
                'nombre' => 'Hotel Campestre El Paraíso',
                'descripcion' => 'Hotel campestre...',
                'precio' => 120000,
                'ubicacion' => 'Km 2 Vía Ortega-Chaparral',
                'latitud' => 3.8123456,
                'longitud' => -75.2234567,
                'imagen' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945',
                'servicios' => 'WiFi, Piscina',
                'capacidad' => 50,
                'disponibilidad' => true
            ]
        ]);
        
    }
}
