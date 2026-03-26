@extends('layouts.app')

@section('content')
<div class="min-h-screen px-4 py-6 bg-gradient-to-br from-amber-50/30 via-orange-50/20 to-rose-50/30 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Encabezado mejorado con temática de cocina -->
        <div class="relative mb-8 overflow-hidden bg-white shadow-xl rounded-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5"></div>
            <div class="absolute top-0 right-0 w-64 h-64 translate-x-32 -translate-y-32 rounded-full bg-gradient-to-br from-primary/10 to-transparent blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-gradient-to-tr from-accent/5 to-transparent blur-2xl"></div>
            <div class="relative px-6 py-8 sm:px-8 sm:py-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-2 shadow-lg bg-gradient-to-br from-primary to-secondary rounded-xl">
                                <i class="text-2xl text-white fas fa-utensil-spoon"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-transparent bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text sm:text-4xl">
                                    Dashboard Cocinero
                                </h1>
                                <p class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                    <i class="text-xs text-orange-500 fas fa-fire"></i>
                                    Panel de control de cocina y preparación
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="flex items-center gap-3 px-4 py-2 border border-gray-100 shadow-sm bg-gray-50/80 backdrop-blur-sm rounded-2xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-xl">
                                <i class="text-xs text-white fas fa-calendar-alt"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium tracking-wider text-gray-500 uppercase">Fecha actual</p>
                                <p class="text-sm font-semibold text-gray-800">{{ now()->format('l, d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Badges de estado de cocina -->
                <div class="flex flex-wrap gap-2 mt-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-primary bg-primary/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-chart-line"></i>
                        Cocina activa
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-secondary bg-secondary/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-clock"></i>
                        Turno: {{ now()->format('H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-accent bg-accent/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-utensils"></i>
                        Preparaciones en curso: 0
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-amber-600 bg-amber-100 backdrop-blur-sm">
                        <i class="text-xs fas fa-hourglass-half"></i>
                        Pendientes: 0
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas mejoradas - KPI Style para cocina -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Ventas Totales -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-primary/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Ventas Totales</p>
                            <p class="text-4xl font-bold text-gray-800">$0.00</p>
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-green-600 bg-green-100 rounded-full">
                                    <i class="mr-1 text-xs fas fa-arrow-up"></i>0%
                                </span>
                                <span class="text-xs text-gray-400">vs mes anterior</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-primary/10 to-primary/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-chart-line text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-primary to-secondary group-hover:scale-x-100"></div>
            </div>

            <!-- Pedidos Hoy con contador de pedidos pendientes -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-secondary/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Pedidos Hoy</p>
                            <p class="text-4xl font-bold text-gray-800">0</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-amber-600 bg-amber-100 rounded-full">
                                    <i class="text-xs fas fa-clock"></i>
                                    <span>Pendientes: 0</span>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-clipboard-list text-secondary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-secondary to-accent group-hover:scale-x-100"></div>
            </div>

            <!-- Clientes Atendidos -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-accent/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Clientes Atendidos</p>
                            <p class="text-4xl font-bold text-gray-800">0</p>
                            <div class="flex items-center gap-2">
                                <i class="text-xs text-accent fas fa-user-check"></i>
                                <span class="text-xs text-gray-500">Hoy: 0 comensales</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-accent/10 to-accent/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-users text-accent"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-accent to-amber-500 group-hover:scale-x-100"></div>
            </div>

            <!-- Productos Agotados con alerta visual -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-red-500/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Productos Agotados</p>
                            <p class="text-4xl font-bold text-rose-600">0</p>
                            <div class="flex items-center gap-1">
                                <i class="text-xs text-amber-500 fas fa-exclamation-triangle"></i>
                                <span class="text-xs text-gray-500">Requieren reposición</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-rose-100 to-amber-50 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-box-open text-rose-500"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-rose-500 to-amber-500 group-hover:scale-x-100"></div>
            </div>
        </div>

        <!-- Secciones principales con temática de cocina -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Ventas Recientes - Preparaciones -->
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-primary to-secondary"></div>
                                <h2 class="text-lg font-bold text-gray-800">Preparaciones Recientes</h2>
                            </div>
                            <p class="text-xs text-gray-500">Últimos pedidos en la cocina</p>
                        </div>
                        <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 group bg-gray-50 text-primary rounded-xl hover:bg-primary hover:text-white hover:shadow-md">
                            <span>Ver todas</span>
                            <i class="text-xs transition-transform fas fa-arrow-right group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-primary/10 to-secondary/10 blur-xl"></div>
                            <div class="relative flex items-center justify-center w-24 h-24 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <i class="text-4xl text-gray-400 fas fa-utensils"></i>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <p class="font-medium text-gray-700">No hay preparaciones pendientes</p>
                            <p class="mt-1 text-sm text-gray-400">Los pedidos aparecerán automáticamente aquí</p>
                            <button class="inline-flex items-center gap-2 px-4 py-2 mt-4 text-sm transition-colors text-primary hover:text-primary/80">
                                <i class="text-xs fas fa-sync-alt"></i>
                                <span>Actualizar estado</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos Más Vendidos - Especialidades -->
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-secondary/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-secondary to-accent"></div>
                                <h2 class="text-lg font-bold text-gray-800">Platos Más Solicitados</h2>
                            </div>
                            <p class="text-xs text-gray-500">Especialidades con mayor demanda</p>
                        </div>
                        <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 group bg-gray-50 text-secondary rounded-xl hover:bg-secondary hover:text-white hover:shadow-md">
                            <span>Ver todos</span>
                            <i class="text-xs transition-transform fas fa-arrow-right group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-secondary/10 to-accent/10 blur-xl"></div>
                            <div class="relative flex items-center justify-center w-24 h-24 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <i class="text-4xl text-gray-400 fas fa-chart-simple"></i>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <p class="font-medium text-gray-700">No hay datos disponibles</p>
                            <p class="mt-1 text-sm text-gray-400">Las estadísticas se generarán automáticamente</p>
                            <button class="inline-flex items-center gap-2 px-4 py-2 mt-4 text-sm transition-colors text-secondary hover:text-secondary/80">
                                <i class="text-xs fas fa-chart-line"></i>
                                <span>Ver tendencias</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección adicional: Estado de Cocina y Preparaciones -->
        <div class="mt-6">
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-accent/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-accent to-amber-500"></div>
                                <h2 class="text-lg font-bold text-gray-800">Estado de Cocina</h2>
                            </div>
                            <p class="text-xs text-gray-500">Monitoreo en tiempo real de la producción</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="p-2 text-gray-400 transition-colors bg-gray-50 rounded-xl hover:bg-gray-100 hover:text-gray-600">
                                <i class="text-xs fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="p-4 text-center bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-green-100 rounded-xl">
                                <i class="text-xl text-green-600 fas fa-check-circle"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">Completados</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">0</p>
                            <p class="mt-1 text-xs text-green-600">Pedidos entregados</p>
                        </div>
                        <div class="p-4 text-center bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-amber-100 rounded-xl">
                                <i class="text-xl text-amber-600 fas fa-clock"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">En Preparación</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">0</p>
                            <p class="mt-1 text-xs text-amber-600">En proceso actualmente</p>
                        </div>
                        <div class="p-4 text-center bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-red-100 rounded-xl">
                                <i class="text-xl text-red-600 fas fa-hourglass-half"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">Pendientes</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">0</p>
                            <p class="mt-1 text-xs text-red-600">Esperando preparación</p>
                        </div>
                    </div>
                    
                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tiempo promedio de preparación</p>
                                <p class="text-2xl font-bold text-primary">0 <span class="text-sm font-normal text-gray-500">minutos</span></p>
                            </div>
                            <div class="flex gap-3">
                                <button class="px-4 py-2 text-sm font-medium transition-all duration-200 text-primary bg-primary/10 rounded-xl hover:bg-primary/20 hover:scale-105">
                                    <i class="mr-2 fas fa-print"></i>
                                    Reporte de Cocina
                                </button>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-primary to-secondary rounded-xl hover:shadow-lg hover:scale-105">
                                    <i class="mr-2 fas fa-utensils"></i>
                                    Nuevo Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de alertas de inventario para cocina -->
        <div class="mt-6">
            <div class="overflow-hidden transition-all duration-300 border shadow-xl bg-gradient-to-r from-amber-50/50 to-orange-50/50 backdrop-blur-sm rounded-2xl border-amber-200">
                <div class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-amber-100 rounded-xl">
                            <i class="text-amber-600 fas fa-exclamation-circle"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-amber-800">Alertas de Inventario</h3>
                            <p class="text-xs text-amber-600">No hay productos con stock bajo en este momento</p>
                        </div>
                        <button class="text-xs font-medium text-amber-700 hover:text-amber-900">
                            Ver inventario →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection