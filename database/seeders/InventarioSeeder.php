<?php

namespace Database\Seeders;

use App\Models\Inventario;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        Inventario::create([
            'ingrediente' => 'Tomate',
            'cantidad' => 50,
            'unidad' => 'kg',
            'stock_minimo' => 10
        ]);

        Inventario::create([
            'ingrediente' => 'Cebolla',
            'cantidad' => 30,
            'unidad' => 'kg',
            'stock_minimo' => 8
        ]);

        Inventario::create([
            'ingrediente' => 'Pollo',
            'cantidad' => 20,
            'unidad' => 'kg',
            'stock_minimo' => 5
        ]);

        Inventario::create([
            'ingrediente' => 'Arroz',
            'cantidad' => 100,
            'unidad' => 'kg',
            'stock_minimo' => 20
        ]);
    }
}