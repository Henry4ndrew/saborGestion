<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Desactivar verificaciones de llaves foráneas al inicio
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            MesaSeeder::class,        // ✅ Agregar MesaSeeder aquí
            // Si tienes otros seeders que dependen de mesas, van después
            // ReservaSeeder::class,
            // PedidoSeeder::class,
        ]);
        
        // 🔥 Reactivar verificaciones de llaves foráneas al final
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}