<?php
// database/seeders/IngredienteSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ingredientes')->insert([
            ['nombre' => 'Pollo', 'unidad_medida' => 'gr'],
            ['nombre' => 'Carne', 'unidad_medida' => 'gr'],
            ['nombre' => 'Arroz', 'unidad_medida' => 'gr'],
            ['nombre' => 'Papa', 'unidad_medida' => 'gr'],
            ['nombre' => 'Lechuga', 'unidad_medida' => 'gr'],
            ['nombre' => 'Tomate', 'unidad_medida' => 'gr'],
        ]);
    }
}