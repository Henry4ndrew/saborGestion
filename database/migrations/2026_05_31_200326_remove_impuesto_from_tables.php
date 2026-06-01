<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('impuesto');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('impuesto');
        });

        Schema::table('consumos', function (Blueprint $table) {
            $table->dropColumn('impuesto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('impuesto', 10, 2)->default(0)->after('subtotal');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('impuesto', 10, 2)->default(0)->after('subtotal');
        });

        Schema::table('consumos', function (Blueprint $table) {
            $table->decimal('impuesto', 10, 2)->default(0)->after('subtotal');
        });
    }
};
