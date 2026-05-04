<?php
// database/migrations/2024_01_01_000004_create_facturas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->string('numero_factura', 20)->unique();
            $table->string('cliente_nombre', 255);
            $table->string('cliente_nit', 20)->nullable();
            $table->string('cliente_telefono', 20)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('impuesto', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'qr', 'transferencia'])->default('efectivo');
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->datetime('fecha_emision');
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
            
            $table->index('numero_factura');
            $table->index('pedido_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};