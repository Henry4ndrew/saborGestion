<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('platos')->insert([
            [
                'nombre' => 'Pollo a la plancha',
                'precio' => 25.50,
                'categoria_id' => 2,
                'disponible' => true,
                'score' => 4.5,
                'descripcion' => 'Pollo acompañado con ensalada'
            ],
            [
                'nombre' => 'Arroz con carne',
                'precio' => 30.00,
                'categoria_id' => 2,
                'disponible' => true,
                'score' => 4.2,
                'descripcion' => 'Arroz con carne sazonada'
            ],
            [
                'nombre' => 'Ensalada fresca',
                'precio' => 15.00,
                'categoria_id' => 1,
                'disponible' => true,
                'score' => 4.0,
                'descripcion' => 'Ensalada de verduras frescas'
            ],
        ]);
    }
}