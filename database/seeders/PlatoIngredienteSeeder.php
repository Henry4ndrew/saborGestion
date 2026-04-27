<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatoIngredienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plato_ingrediente')->insert([
            [
                'plato_id' => 1,
                'ingrediente_id' => 1,
                'cantidad' => 200
            ],
            [
                'plato_id' => 1,
                'ingrediente_id' => 5,
                'cantidad' => 50
            ],
            [
                'plato_id' => 2,
                'ingrediente_id' => 2,
                'cantidad' => 200
            ],
            [
                'plato_id' => 2,
                'ingrediente_id' => 3,
                'cantidad' => 150
            ],
        ]);
    }
}