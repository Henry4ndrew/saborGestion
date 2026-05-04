<?php
// database/migrations/2024_01_01_000002_create_detalle_pedidos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('plato_id')->constrained('platos')->onDelete('restrict');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'])->default('pendiente');
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('estado');
            $table->index('pedido_id');
            $table->index('plato_id');
            
            // Evitar duplicados del mismo plato en el mismo pedido
            $table->unique(['pedido_id', 'plato_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};