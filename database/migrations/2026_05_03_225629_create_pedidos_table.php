<?php
// database/migrations/2024_01_01_000001_create_pedidos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    

// database/migrations/2024_01_01_000001_create_pedidos_table.php

public function up(): void
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->string('numero_pedido', 20)->nullable()->unique(); // unique pero nullable
        $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete();
        $table->string('cliente_nombre', 255)->nullable();
        $table->string('cliente_telefono', 20)->nullable();
        $table->text('direccion')->nullable();
        $table->enum('tipo_pedido', ['mesa', 'delivery', 'para_llevar'])->default('mesa');
        $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado', 'facturado'])->default('pendiente');
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('impuesto', 10, 2)->default(0);
        $table->decimal('descuento', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->text('notas')->nullable();
        $table->datetime('fecha_hora_estimada')->nullable();
        $table->datetime('fecha_hora_entrega')->nullable();
        $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
        
        $table->index('estado');
        $table->index('tipo_pedido');
        $table->index('created_at');
        $table->index('numero_pedido');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};