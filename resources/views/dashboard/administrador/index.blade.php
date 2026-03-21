@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gastronomico-primary">Dashboard Administrador</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Ventas Totales</p>
                    <p class="text-2xl font-bold">$0.00</p>
                </div>
                <i class="fas fa-chart-line text-3xl text-gastronomico-primary"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Pedidos Hoy</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <i class="fas fa-shopping-cart text-3xl text-gastronomico-secondary"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Clientes Atendidos</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <i class="fas fa-users text-3xl text-gastronomico-accent"></i>
            </div>
        </div>
        
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Productos Agotados</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <i class="fas fa-exclamation-triangle text-3xl text-gastronomico-warning"></i>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Ventas Recientes</h2>
            <p class="text-gray-500">No hay ventas registradas</p>
        </div>
        
        <div class="card">
            <h2 class="text-xl font-bold mb-4">Productos Más Vendidos</h2>
            <p class="text-gray-500">No hay datos disponibles</p>
        </div>
    </div>
</div>
@endsection