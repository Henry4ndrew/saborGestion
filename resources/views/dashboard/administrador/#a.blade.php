@extends('layouts.app')

@section('content')
<div class="relative min-h-screen">
    <!-- Imagen de fondo sutil con overlay -->
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/95 via-orange-50/90 to-rose-50/95"></div>
        <img src="{{ asset('images/restaurant-bg-pattern.jpg') }}" 
             alt="Fondo restaurante"
             class="object-cover w-full h-full opacity-10"
             loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent"></div>
    </div>

    <div class="relative z-10 px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <!-- Encabezado con imagen decorativa -->
            <div class="relative mb-8 overflow-hidden shadow-xl bg-white/90 backdrop-blur-sm rounded-2xl">
                <!-- Imagen decorativa lateral -->
                <div class="absolute top-0 right-0 hidden w-1/3 h-full opacity-10 lg:block">
                    <img src="{{ asset('images/chef-cooking.jpg') }}" 
                         alt="Chef cocinando"
                         class="object-cover w-full h-full"
                         loading="lazy">
                </div>
                
                <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5"></div>
                <div class="absolute top-0 right-0 w-64 h-64 translate-x-32 -translate-y-32 rounded-full bg-gradient-to-br from-primary/10 to-transparent blur-3xl"></div>
                
                <div class="relative px-6 py-8 sm:px-8 sm:py-10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <div class="p-2 shadow-lg bg-gradient-to-br from-primary to-secondary rounded-xl">
                                    <i class="text-2xl text-white fas fa-utensils"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold tracking-tight text-transparent bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text sm:text-4xl">
                                        Dashboard Administrador
                                    </h1>
                                    <p class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                        <i class="text-xs fas fa-fire text-amber-500"></i>
                                        Panel de control central de SaborGestion
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <div class="flex items-center gap-3 px-4 py-2 border border-gray-100 shadow-sm bg-white/80 backdrop-blur-sm rounded-2xl">
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
                    
                    <!-- Badges de bienvenida con iconos mejorados -->
                    <div class="flex flex-wrap gap-2 mt-6">
                        <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-primary bg-primary/10 backdrop-blur-sm">
                            <i class="text-xs fas fa-chart-line"></i>
                            Resumen en tiempo real
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-secondary bg-secondary/10 backdrop-blur-sm">
                            <i class="text-xs fas fa-store"></i>
                            Restaurante activo
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-accent bg-accent/10 backdrop-blur-sm">
                            <i class="text-xs fas fa-clock"></i>
                            Última actualización: ahora
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas con imágenes pequeñas -->
            <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Ventas Totales con imagen decorativa -->
                <div class="relative overflow-hidden transition-all duration-500 bg-white/90 backdrop-blur-sm shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                    <div class="absolute w-32 h-32 -top-10 -right-10 opacity-10">
                        <img src="{{ asset('images/coin-icon.png') }}" alt="Moneda" class="object-contain w-full h-full">
                    </div>
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

                <!-- Pedidos Hoy con imagen decorativa -->
                <div class="relative overflow-hidden transition-all duration-500 bg-white/90 backdrop-blur-sm shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                    <div class="absolute w-32 h-32 -bottom-10 -right-10 opacity-10">
                        <img src="{{ asset('images/order-icon.png') }}" alt="Pedido" class="object-contain w-full h-full">
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-secondary/5 to-transparent blur-2xl"></div>
                    <div class="relative p-6">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Pedidos Hoy</p>
                                <p class="text-4xl font-bold text-gray-800">0</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">Pendientes:</span>
                                    <span class="text-xs font-semibold text-amber-600">0</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-2xl group-hover:scale-110">
                                <i class="text-3xl fas fa-shopping-cart text-secondary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-secondary to-accent group-hover:scale-x-100"></div>
                </div>

                <!-- Resto de tarjetas similar... -->
            </div>

            <!-- Sección de Productos Destacados con imágenes reales -->
            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                <!-- Productos Más Vendidos con imágenes -->
                <div class="overflow-hidden transition-all duration-300 shadow-xl bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-2xl">
                    <div class="relative px-6 py-5 border-b border-gray-100">
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/3 to-transparent"></div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1 h-6 rounded-full bg-gradient-to-b from-secondary to-accent"></div>
                                    <h2 class="text-lg font-bold text-gray-800">Platos Estrella</h2>
                                </div>
                                <p class="text-xs text-gray-500">Los más solicitados por nuestros comensales</p>
                            </div>
                            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 group bg-gray-50 text-secondary rounded-xl hover:bg-secondary hover:text-white hover:shadow-md">
                                <span>Ver todos</span>
                                <i class="text-xs transition-transform fas fa-arrow-right group-hover:translate-x-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($productosDestacados->count() > 0)
                            <div class="space-y-4">
                                @foreach($productosDestacados as $producto)
                                <div class="flex items-center gap-4 p-3 transition-all rounded-xl hover:bg-gray-50">
                                    <div class="w-16 h-16 overflow-hidden rounded-xl">
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             alt="{{ $producto->nombre }}"
                                             class="object-cover w-full h-full"
                                             loading="lazy">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $producto->nombre }}</h3>
                                        <p class="text-sm text-gray-500">Ventas: {{ $producto->ventas }} unidades</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary">${{ number_format($producto->precio, 2) }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="relative">
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-secondary/10 to-accent/10 blur-xl"></div>
                                    <div class="relative flex items-center justify-center w-24 h-24 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                        <i class="text-4xl text-gray-400 fas fa-utensils"></i>
                                    </div>
                                </div>
                                <div class="mt-6 text-center">
                                    <p class="font-medium text-gray-700">Próximamente platos destacados</p>
                                    <p class="mt-1 text-sm text-gray-400">Las estadísticas se generarán automáticamente</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Galería de Especialidades -->
                <div class="overflow-hidden transition-all duration-300 shadow-xl bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-2xl">
                    <div class="relative px-6 py-5 border-b border-gray-100">
                        <div class="absolute inset-0 bg-gradient-to-r from-accent/3 to-transparent"></div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1 h-6 rounded-full bg-gradient-to-b from-accent to-amber-500"></div>
                                    <h2 class="text-lg font-bold text-gray-800">Especialidades de la Casa</h2>
                                </div>
                                <p class="text-xs text-gray-500">Nuestras recomendaciones del chef</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative overflow-hidden rounded-xl group">
                                <img src="{{ asset('images/paella.jpg') }}" 
                                     alt="Paella Valenciana"
                                     class="object-cover w-full h-32 transition-transform duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 flex items-end p-3 bg-gradient-to-t from-black/60 to-transparent">
                                    <p class="text-sm font-medium text-white">Paella Valenciana</p>
                                </div>
                            </div>
                            <div class="relative overflow-hidden rounded-xl group">
                                <img src="{{ asset('images/risotto.jpg') }}" 
                                     alt="Risotto de Mariscos"
                                     class="object-cover w-full h-32 transition-transform duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 flex items-end p-3 bg-gradient-to-t from-black/60 to-transparent">
                                    <p class="text-sm font-medium text-white">Risotto de Mariscos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Efecto de parallax suave para imágenes de fondo */
    .parallax-bg {
        transform: translateY(0);
        transition: transform 0.3s ease-out;
    }
    
    .hover\:scale-110:hover {
        transform: scale(1.1);
    }
    
    /* Animación sutil para imágenes */
    @keyframes subtleZoom {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .animate-subtle-zoom {
        animation: subtleZoom 20s ease-in-out infinite;
    }
</style>
@endpush