<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CierreCajaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController; 
use App\Http\Controllers\IngredienteController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Ruta para mostrar la página principal con el botón de login
Route::get('/inicio', function () {
    return view('home');
})->name('inicio');

Route::middleware(['auth'])->group(function () {
    // Dashboards
    Route::get('/dashboard/administrador', [DashboardController::class, 'administrador'])->name('dashboard.administrador');
    Route::get('/dashboard/mesero', [DashboardController::class, 'mesero'])->name('dashboard.mesero');
    Route::get('/dashboard/cocinero', [DashboardController::class, 'cocinero'])->name('dashboard.cocinero');
    Route::get('/dashboard/cajero', [DashboardController::class, 'cajero'])->name('dashboard.cajero');
    Route::get('/dashboard/cliente', [DashboardController::class, 'cliente'])->name('dashboard.cliente');
    
    // Gestión de Platos (Admin y Cocinero)
    Route::resource('platos', PlatoController::class)->middleware('role:admin,cocinero');
    Route::post('/platos/{plato}/toggle-disponible', [PlatoController::class, 'toggleDisponible'])
        ->name('platos.toggle-disponible')
        ->middleware('role:admin,cocinero');
    
    // Inventario
    Route::resource('inventario', InventarioController::class)->middleware('role:admin,cocinero');
    
    // Mesas
    Route::resource('mesas', MesaController::class)->middleware('role:admin,mesero');
    
    // Pedidos
    Route::resource('pedidos', PedidoController::class)->middleware('role:admin,cajero');
    
    // Comandas
    Route::resource('comandas', ComandaController::class)->middleware('role:admin,cajero');
    
    // Delivery
    Route::resource('delivery', DeliveryController::class)->middleware('role:admin,cajero');
    
    // Facturas
    Route::resource('facturas', FacturaController::class)->middleware('role:admin,cajero');
    
    // Pagos
    Route::resource('pagos', PagoController::class)->middleware('role:admin,cajero');
    
    // Cierre de Caja
    Route::resource('cierres', CierreCajaController::class)->middleware('role:admin,cajero');
    
    // Gestión de Categorías (Solo Admin) - CON todas las rutas CRUD
    Route::resource('categorias', CategoriaController::class)->middleware('role:admin');
    Route::post('/categorias/{categoria}/toggle-activo', [CategoriaController::class, 'toggleActivo'])
        ->name('categorias.toggle-activo')
        ->middleware('role:admin');
    
    // Gestión de Ingredientes (Admin y Cocinero) - CON todas las rutas CRUD
    Route::resource('ingredientes', IngredienteController::class)->middleware('role:admin,cocinero');

    // Usuarios
    Route::resource('usuarios', UsuarioController::class)->middleware('role:admin');
});

require __DIR__.'/auth.php';