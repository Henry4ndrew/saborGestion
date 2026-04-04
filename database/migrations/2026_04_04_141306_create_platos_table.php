// database/migrations/2024_01_01_000002_create_platos_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->foreignId('categoria_id')->constrained()->onDelete('restrict');
            $table->string('imagen')->nullable();
            $table->boolean('disponible')->default(true);
            $table->decimal('score', 2, 1)->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platos');
    }
};