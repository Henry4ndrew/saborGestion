@php
    $user = Auth::user();
    $role = $user->role;
@endphp

<aside class="bg-primary text-white shadow-xl transition-all duration-300 ease-in-out flex flex-col h-full"
       :class="{
           'w-72': sidebarExpanded,
           'w-20': !sidebarExpanded && windowWidth >= 1024,
           'w-72': mobileSidebarOpen && windowWidth < 1024
       }">
    
    <!-- Logo compacto -->
    <div class="p-4 sm:p-5 border-b border-white/10 flex items-center justify-between">
        <div class="flex items-center gap-2 sm:gap-3 overflow-hidden">
            <div class="flex-shrink-0">
                <img src="{{ asset('logo.png') }}" alt="SaborGestion Logo" 
                     class="object-contain w-10 h-10 sm:w-12 sm:h-12 rounded-full">
            </div>
            <div x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="whitespace-nowrap">
                <h1 class="text-lg sm:text-xl font-bold text-white">SaborGestion</h1>
                <p class="text-[10px] sm:text-xs text-white/70">Sistema de Gestión</p>
            </div>
        </div>
    </div>

    <!-- Navegación principal -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 sm:py-6 px-2 sm:px-3 custom-scrollbar">
        <div class="space-y-1">
            
            <!-- Inteligencia de Negocios -->
            @if(in_array($role, ['admin', 'mesero', 'cocinero', 'cajero']))
            <div x-data="{ open: true }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-chart-line text-white/80 text-base sm:text-lg w-5 group-hover:text-white transition-colors"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                              x-transition.duration.200 
                              class="text-xs sm:text-sm font-medium text-white/80 group-hover:text-white whitespace-nowrap">
                            Inteligencia de Negocios
                        </span>
                    </div>
                    <i x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="fas text-xs text-white/50 transition-transform duration-200"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="ml-6 sm:ml-10 mt-1 space-y-1">
                    @if($role == 'admin')
                        <a href="{{ route('dashboard.administrador') }}" 
                           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                            <i class="fas fa-chart-pie text-[10px] sm:text-xs w-4"></i>
                            <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Dashboard Admin</span>
                        </a>
                    @endif
                    @if($role == 'mesero')
                        <a href="{{ route('dashboard.mesero') }}" 
                           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                            <i class="fas fa-chart-simple text-[10px] sm:text-xs w-4"></i>
                            <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Dashboard Mesero</span>
                        </a>
                    @endif
                    @if($role == 'cocinero')
                        <a href="{{ route('dashboard.cocinero') }}" 
                           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                            <i class="fas fa-chart-line text-[10px] sm:text-xs w-4"></i>
                            <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Dashboard Cocinero</span>
                        </a>
                    @endif
                    @if($role == 'cajero')
                        <a href="{{ route('dashboard.cajero') }}" 
                           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                            <i class="fas fa-chart-bar text-[10px] sm:text-xs w-4"></i>
                            <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Dashboard Cajero</span>
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Catálogo y Menú -->
            @if(in_array($role, ['admin', 'cocinero']))
            <div x-data="{ open: true }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-book-open text-white/80 text-base sm:text-lg w-5 group-hover:text-white transition-colors"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                              x-transition.duration.200 
                              class="text-xs sm:text-sm font-medium text-white/80 group-hover:text-white whitespace-nowrap">
                            Catálogo y Menú
                        </span>
                    </div>
                    <i x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="fas text-xs text-white/50 transition-transform duration-200"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="ml-6 sm:ml-10 mt-1 space-y-1">
                    <a href="{{ route('platos.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-utensils text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Platos</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Mesas -->
            @if(in_array($role, ['admin', 'mesero']))
            <div x-data="{ open: true }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-chair text-white/80 text-base sm:text-lg w-5 group-hover:text-white transition-colors"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                              x-transition.duration.200 
                              class="text-xs sm:text-sm font-medium text-white/80 group-hover:text-white whitespace-nowrap">
                            Gestión de Mesas
                        </span>
                    </div>
                    <i x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="fas text-xs text-white/50 transition-transform duration-200"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="ml-6 sm:ml-10 mt-1">
                    <a href="{{ route('mesas.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-table text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Mesas</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Operaciones -->
            @if(in_array($role, ['admin', 'cajero']))
            <div x-data="{ open: true }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-cash-register text-white/80 text-base sm:text-lg w-5 group-hover:text-white transition-colors"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                              x-transition.duration.200 
                              class="text-xs sm:text-sm font-medium text-white/80 group-hover:text-white whitespace-nowrap">
                            Operaciones
                        </span>
                    </div>
                    <i x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="fas text-xs text-white/50 transition-transform duration-200"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="ml-6 sm:ml-10 mt-1 space-y-1">
                    <a href="{{ route('pedidos.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-clipboard-list text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Pedidos</span>
                    </a>
                    <a href="{{ route('comandas.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-receipt text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Comandas</span>
                    </a>
                    <a href="{{ route('delivery.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-motorcycle text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Delivery</span>
                    </a>
                    
                    <div class="h-px bg-white/10 my-2"></div>
                    
                    <a href="{{ route('facturas.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-file-invoice text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Pre-factura</span>
                    </a>
                    <a href="{{ route('pagos.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-credit-card text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Pagos</span>
                    </a>
                    <a href="{{ route('cierres.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-cash-register text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Cierre de Caja</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Administración -->
            @if($role == 'admin')
            <div x-data="{ open: true }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <i class="fas fa-user-shield text-white/80 text-base sm:text-lg w-5 group-hover:text-white transition-colors"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                              x-transition.duration.200 
                              class="text-xs sm:text-sm font-medium text-white/80 group-hover:text-white whitespace-nowrap">
                            Administración
                        </span>
                    </div>
                    <i x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" 
                       :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="fas text-xs text-white/50 transition-transform duration-200"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse
                     x-cloak
                     class="ml-6 sm:ml-10 mt-1">
                    <a href="{{ route('usuarios.index') }}" 
                       class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-users text-[10px] sm:text-xs w-4"></i>
                        <span x-show="sidebarExpanded || (windowWidth < 1024 && mobileSidebarOpen)" class="whitespace-nowrap">Usuarios</span>
                    </a>
                </div>
            </div>
            @endif

        </div>
    </nav>
</aside>

<!-- Estilos adicionales mejorados -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
    /* Mejoras en las transiciones */
    [x-cloak] {
        display: none !important;
    }
    
    /* Transición suave para el colapso */
    .x-collapse {
        transition: all 0.2s ease-out;
    }
</style>