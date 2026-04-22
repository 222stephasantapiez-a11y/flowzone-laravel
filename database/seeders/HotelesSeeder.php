<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hoteles')->insert([
            [
                'nombre' => 'Hotel Casa Mejia',
                'descripcion' => 'Hotel Campestre en Ortega',
                'precio' => 70000,
                'ubicacion' => 'Cra. 9 #3-55, Ortega, Tolima',
                'latitud' => 4.4389,
                'longitud' => -75.2322,
                'imagen' => 'https://casamejia.com/wp-content/uploads/2023/09/WhatsApp-Image-2023-09-11-at-11.44.17-AM.jpeg',
                'servicios' => 'WiFi, Parqueadero',
                'capacidad' => 10,
                'disponibilidad' => true,
                'telefono' => '3186459236',
                'email' => 'hotelcasamejia@gmail.com',
                'empresa_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Hotel Casa Vieja',
                'descripcion' => 'Vista natural',
                'precio' => 80000,
                'ubicacion' => 'Carrera 3 #3 - 14, Ortega, Tolima',
                'latitud' => 4.4400,
                'longitud' => -75.2300,
                'imagen' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/307856364.jpg',
                'servicios' => 'Wifi, Piscina, Parqueadero',
                'capacidad' => 7,
                'disponibilidad' => true,
                'telefono' => '3168425978',
                'email' => 'hotelcasavieja@gmail.com',
                'empresa_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
