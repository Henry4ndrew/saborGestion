@extends('layouts.app')

@section('content')
<div class="min-h-screen px-3 py-4 bg-gradient-to-br from-amber-50/30 via-orange-50/20 to-rose-50/30 sm:px-4 lg:px-6">
    <div class="mx-auto max-w-7xl">
        <!-- Encabezado con tarjeta visual -->
        <div class="relative mb-4 sm:mb-6 overflow-hidden bg-white shadow-xl rounded-xl sm:rounded-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5"></div>
            <div class="absolute top-0 right-0 hidden w-64 h-64 translate-x-32 -translate-y-32 rounded-full md:block bg-gradient-to-br from-primary/10 to-transparent blur-3xl"></div>
            
            <div class="relative px-4 py-5 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-1 sm:space-y-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1.5 sm:p-2">
                                <img src="{{ asset('logo.png') }}" alt="SaborGestion Logo" 
                                    class="object-contain w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full">
                            </div>
                            <div>
                                <h1 class="text-xl font-bold tracking-tight text-transparent bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text sm:text-2xl lg:text-3xl xl:text-4xl">
                                    Dashboard Administrador
                                </h1>
                                <p class="flex items-center gap-1.5 mt-0.5 text-xs text-gray-500 sm:text-sm">
                                    <i class="text-[10px] fas fa-fire text-amber-500"></i>
                                    Panel de control central de SaborGestion
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="flex items-center gap-2 sm:gap-3 px-2 py-1.5 sm:px-3 sm:py-2 border border-gray-100 shadow-sm bg-gray-50/80 backdrop-blur-sm rounded-xl sm:rounded-2xl">
                            <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-primary to-secondary rounded-lg">
                                <i class="text-[8px] sm:text-[10px] text-white fas fa-calendar-alt"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] sm:text-[10px] font-medium tracking-wider text-gray-500 uppercase">Fecha actual</p>
                                <p class="text-xs font-semibold text-gray-800 sm:text-sm">{{ now()->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Badges de bienvenida -->
                <div class="flex flex-wrap gap-1.5 sm:gap-2 mt-4 sm:mt-6">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-medium rounded-full text-primary bg-primary/10">
                        <i class="fas fa-chart-line"></i>
                        En vivo
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-medium rounded-full text-secondary bg-secondary/10">
                        <i class="fas fa-store"></i>
                        Activo
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas - KPI Style -->
        <div class="grid grid-cols-1 gap-3 mb-6 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:mb-8">
            <!-- Ventas Totales -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg group rounded-xl sm:rounded-2xl hover:shadow-xl">
                <div class="relative p-3 sm:p-5">
                    <div class="flex items-start justify-between">
                        <div class="space-y-0.5 sm:space-y-1">
                            <p class="text-[9px] sm:text-[10px] font-semibold tracking-wider text-gray-400 uppercase">Ventas Totales</p>
                            <p class="text-xl font-bold text-gray-800 sm:text-2xl lg:text-3xl">$0.00</p>
                            <div class="flex items-center gap-0.5 sm:gap-1">
                                <span class="px-1 py-0.5 text-[9px] sm:text-[10px] font-medium text-green-600 bg-green-100 rounded-md">
                                    <i class="mr-0.5 fas fa-arrow-up"></i>0%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 transition-transform duration-300 bg-primary/10 rounded-lg sm:rounded-xl group-hover:scale-110">
                            <i class="text-base sm:text-lg lg:text-xl fas fa-chart-line text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-primary to-secondary"></div>
            </div>

            <!-- Pedidos Hoy -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg group rounded-xl sm:rounded-2xl hover:shadow-xl">
                <div class="relative p-3 sm:p-5">
                    <div class="flex items-start justify-between">
                        <div class="space-y-0.5 sm:space-y-1">
                            <p class="text-[9px] sm:text-[10px] font-semibold tracking-wider text-gray-400 uppercase">Pedidos Hoy</p>
                            <p class="text-xl font-bold text-gray-800 sm:text-2xl lg:text-3xl">0</p>
                            <p class="text-[9px] sm:text-[10px] text-gray-500">Pendientes: <span class="font-bold text-amber-600">0</span></p>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 transition-transform duration-300 bg-secondary/10 rounded-lg sm:rounded-xl group-hover:scale-110">
                            <i class="text-base sm:text-lg lg:text-xl fas fa-shopping-cart text-secondary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary to-accent"></div>
            </div>

            <!-- Clientes -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg group rounded-xl sm:rounded-2xl hover:shadow-xl">
                <div class="relative p-3 sm:p-5">
                    <div class="flex items-start justify-between">
                        <div class="space-y-0.5 sm:space-y-1">
                            <p class="text-[9px] sm:text-[10px] font-semibold tracking-wider text-gray-400 uppercase">Clientes</p>
                            <p class="text-xl font-bold text-gray-800 sm:text-2xl lg:text-3xl">0</p>
                            <p class="text-[9px] sm:text-[10px] text-gray-500">Hoy: <span class="font-bold text-accent">0</span></p>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 transition-transform duration-300 bg-accent/10 rounded-lg sm:rounded-xl group-hover:scale-110">
                            <i class="text-base sm:text-lg lg:text-xl fas fa-users text-accent"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-accent to-amber-500"></div>
            </div>

            <!-- Stock -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg group rounded-xl sm:rounded-2xl hover:shadow-xl">
                <div class="relative p-3 sm:p-5">
                    <div class="flex items-start justify-between">
                        <div class="space-y-0.5 sm:space-y-1">
                            <p class="text-[9px] sm:text-[10px] font-semibold tracking-wider text-gray-400 uppercase">Agotados</p>
                            <p class="text-xl font-bold text-rose-600 sm:text-2xl lg:text-3xl">0</p>
                            <p class="text-[9px] sm:text-[10px] text-gray-500">Requieren reposición</p>
                        </div>
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 transition-transform duration-300 bg-rose-50 rounded-lg sm:rounded-xl group-hover:scale-110">
                            <i class="text-base sm:text-lg lg:text-xl fas fa-box-open text-rose-500"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500"></div>
            </div>
        </div>

        <!-- Secciones de Gráficos/Tablas -->
        <div class="grid grid-cols-1 gap-4 lg:gap-6 lg:grid-cols-2">
            <!-- Ventas Recientes -->
            <div class="overflow-hidden bg-white shadow-lg rounded-xl sm:rounded-2xl">
                <div class="px-4 py-3 sm:px-5 sm:py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-primary/5 to-transparent">
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">Ventas Recientes</h2>
                    <button class="text-xs font-medium text-primary hover:underline">Ver todas</button>
                </div>
                <div class="p-4 sm:p-6 text-center">
                    <div class="py-6 sm:py-8">
                        <i class="mb-2 sm:mb-3 text-3xl sm:text-4xl text-gray-200 fas fa-receipt"></i>
                        <p class="text-xs sm:text-sm text-gray-500">No hay ventas registradas hoy</p>
                    </div>
                </div>
            </div>

            <!-- Productos Estrella -->
            <div class="overflow-hidden bg-white shadow-lg rounded-xl sm:rounded-2xl">
                <div class="px-4 py-3 sm:px-5 sm:py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-secondary/5 to-transparent">
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">Platos Estrella</h2>
                    <button class="text-xs font-medium text-secondary hover:underline">Ver todos</button>
                </div>
                <div class="p-4 sm:p-6">
                    @if(isset($productosDestacados) && $productosDestacados->count() > 0)
                        <!-- Lista de productos -->
                    @else
                        <div class="py-6 sm:py-8 text-center">
                            <i class="mb-2 sm:mb-3 text-3xl sm:text-4xl text-gray-200 fas fa-utensils"></i>
                            <p class="text-xs sm:text-sm text-gray-500">Aún no hay platos destacados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection