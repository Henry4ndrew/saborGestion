@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <h1 class="text-3xl font-bold text-primary">
        Dashboard Cajero
    </h1>
    
    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-muted">Ventas Totales</p>
                    <p class="text-2xl font-bold text-text">$0.00</p>
                </div>
                <i class="fas fa-chart-line text-3xl text-primary"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-muted">Pedidos Hoy</p>
                    <p class="text-2xl font-bold text-text">0</p>
                </div>
                <i class="fas fa-shopping-cart text-3xl text-secondary"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-muted">Clientes Atendidos</p>
                    <p class="text-2xl font-bold text-text">0</p>
                </div>
                <i class="fas fa-users text-3xl text-accent"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-muted">Productos Agotados</p>
                    <p class="text-2xl font-bold text-text">0</p>
                </div>
                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
        </div>

    </div>
    
    <!-- Secciones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card">
            <h2 class="text-xl font-bold mb-4 text-primary">
                Ventas Recientes
            </h2>
            <p class="text-muted">No hay ventas registradas</p>
        </div>
        
        <div class="card">
            <h2 class="text-xl font-bold mb-4 text-primary">
                Productos Más Vendidos
            </h2>
            <p class="text-muted">No hay datos disponibles</p>
        </div>

    </div>

</div>
@endsection