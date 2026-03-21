<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->nullable()->constrained()->onDelete('set null');
            $table->string('cliente')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'preparando', 'listo', 'entregado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};