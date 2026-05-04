<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TestConnection;

class TestTiDBConnection extends Command
{
    protected $signature   = 'tidb:test';
    protected $description = 'Prueba la conexión a TiDB Cloud usando Eloquent ORM';

    public function handle(): int
    {
        $this->info('═══════════════════════════════════════');
        $this->info('   Prueba de Conexión a TiDB Cloud');
        $this->info('═══════════════════════════════════════');

        // ── 1. Conexión básica PDO ──────────────────────────────
        $this->newLine();
        $this->line('📡 [1/4] Verificando conexión PDO...');
        try {
            DB::connection()->getPdo();
            $this->info('    ✅ Conexión PDO establecida correctamente');
        } catch (\Exception $e) {
            $this->error('    ❌ Error de conexión PDO: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── 2. Verificar SSL ────────────────────────────────────
        $this->line('🔒 [2/4] Verificando cifrado SSL...');
        try {
            $sslStatus = DB::select("SHOW STATUS LIKE 'Ssl_cipher'");
            $cipher    = $sslStatus[0]->Value ?? '';

            if (!empty($cipher)) {
                $this->info("    ✅ SSL activo — Cipher: {$cipher}");
                $sslActivo = true;
            } else {
                $this->warn('    ⚠️  SSL NO está activo (conexión sin cifrado)');
                $sslActivo = false;
            }
        } catch (\Exception $e) {
            $this->error('    ❌ No se pudo verificar SSL: ' . $e->getMessage());
            $sslActivo = false;
        }

        // ── 3. Operaciones Eloquent (INSERT y SELECT) ───────────
        $this->line('💾 [3/4] Probando Eloquent ORM...');
        try {
            // INSERT usando Eloquent
            $registro = TestConnection::create([
                'mensaje'    => 'Conexión exitosa desde ' . gethostname(),
                'ssl_activo' => $sslActivo,
            ]);
            $this->info("    ✅ INSERT exitoso — ID: {$registro->id}");

            // SELECT usando Eloquent
            $encontrado = TestConnection::find($registro->id);
            $this->info("    ✅ SELECT exitoso — Mensaje: {$encontrado->mensaje}");

            // UPDATE usando Eloquent
            $encontrado->update(['mensaje' => 'Actualizado: ' . now()->toDateTimeString()]);
            $this->info("    ✅ UPDATE exitoso");

            // DELETE usando Eloquent
            $encontrado->delete();
            $this->info("    ✅ DELETE exitoso");

        } catch (\Exception $e) {
            $this->error('    ❌ Error en Eloquent: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── 4. Información del servidor ─────────────────────────
        $this->line('ℹ️  [4/4] Información del servidor TiDB...');
        try {
            $version = DB::select('SELECT VERSION() as version')[0]->version;
            $dbName  = DB::select('SELECT DATABASE() as db')[0]->db;
            $this->info("    ✅ Versión TiDB: {$version}");
            $this->info("    ✅ Base de datos: {$dbName}");
        } catch (\Exception $e) {
            $this->warn('    ⚠️  No se pudo obtener info del servidor: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('   ✅ TODAS LAS PRUEBAS PASARON');
        $this->info('═══════════════════════════════════════');

        return self::SUCCESS;
    }
}
