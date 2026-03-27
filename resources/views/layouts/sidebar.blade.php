@php
    $user = Auth::user();
    $role = $user ? $user->role : null;
@endphp

<aside x-data="{ 
    menuInteligencia: false,
    menuCatalogo: false,
    menuMesas: false,
    menuOperaciones: false,
    menuAdministracion: false
}" 
       x-cloak
       :class="$store.sidebar.sidebarOpen ? 'w-72' : 'w-20'" 
       class="sticky top-0 z-30 flex flex-col h-screen text-white transition-all duration-300 ease-in-out shadow-2xl bg-gradient-to-b from-primary to-primary/95"
       :style="$store.sidebar.sidebarOpen ? 'min-width: 288px' : 'min-width: 80px'">
    
    <!-- Logo y branding -->
    <div class="flex items-center justify-between p-5 border-b border-white/15">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="relative flex-shrink-0">
                <div class="absolute inset-0 rounded-full bg-white/20 blur-sm"></div>
                <i class="relative z-10 text-2xl text-white fas fa-utensils"></i>
            </div>
            <div x-show="$store.sidebar.sidebarOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="whitespace-nowrap">
                <h1 class="text-xl font-bold tracking-tight text-transparent bg-gradient-to-r from-white to-white/80 bg-clip-text">
                    SaborGestion
                </h1>
                <p class="text-xs text-white/60 mt-0.5">Sistema de Gestión</p>
            </div>
        </div>
        
        <!-- Botón toggle hamburguesa -->
        <button @click="$store.sidebar.toggle()" 
                class="p-2 transition-all duration-200 rounded-xl bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-primary/50 group">
            <i class="text-lg transition-colors fas fa-bars text-white/70 group-hover:text-white"></i>
        </button>
    </div>

    <!-- Navegación principal -->
    <nav class="flex-1 px-3 py-6 overflow-x-hidden overflow-y-auto custom-scrollbar">
        <div class="space-y-1.5">
            
            <!-- Inteligencia de Negocios -->
            @if(in_array($role, ['admin', 'mesero', 'cocinero', 'cajero']))
            <div>
                <button @click="menuInteligencia = !menuInteligencia" 
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-3">
                        <i class="w-5 text-lg transition-colors fas fa-chart-line text-white/70 group-hover:text-white"></i>
                        <span x-show="$store.sidebar.sidebarOpen" 
                              class="text-sm font-medium text-white/70 group-hover:text-white whitespace-nowrap">
                            Inteligencia de Negocios
                        </span>
                    </div>
                    <i x-show="$store.sidebar.sidebarOpen" 
                       :class="menuInteligencia ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="text-xs transition-transform duration-200 fas text-white/40"></i>
                </button>
                
                <div x-show="menuInteligencia" 
                     x-cloak
                     x-transition.duration.200ms
                     class="ml-11 mt-1 space-y-0.5">
                    @if($role == 'admin')
                        <a href="{{ route('dashboard.administrador') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                            <i class="w-4 text-xs fas fa-chart-pie text-white/50 group-hover:text-white/80"></i>
                            <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Dashboard Admin</span>
                        </a>
                    @endif
                    @if($role == 'mesero')
                        <a href="{{ route('dashboard.mesero') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                            <i class="w-4 text-xs fas fa-chart-simple text-white/50 group-hover:text-white/80"></i>
                            <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Dashboard Mesero</span>
                        </a>
                    @endif
                    @if($role == 'cocinero')
                        <a href="{{ route('dashboard.cocinero') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                            <i class="w-4 text-xs fas fa-chart-line text-white/50 group-hover:text-white/80"></i>
                            <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Dashboard Cocinero</span>
                        </a>
                    @endif
                    @if($role == 'cajero')
                        <a href="{{ route('dashboard.cajero') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                            <i class="w-4 text-xs fas fa-chart-bar text-white/50 group-hover:text-white/80"></i>
                            <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Dashboard Cajero</span>
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Catálogo y Menú -->
            @if(in_array($role, ['admin', 'cocinero']))
            <div>
                <button @click="menuCatalogo = !menuCatalogo" 
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-3">
                        <i class="w-5 text-lg transition-colors fas fa-book-open text-white/70 group-hover:text-white"></i>
                        <span x-show="$store.sidebar.sidebarOpen" 
                              class="text-sm font-medium text-white/70 group-hover:text-white whitespace-nowrap">
                            Catálogo y Menú
                        </span>
                    </div>
                    <i x-show="$store.sidebar.sidebarOpen" 
                       :class="menuCatalogo ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="text-xs transition-transform duration-200 fas text-white/40"></i>
                </button>
                
                <div x-show="menuCatalogo" 
                     x-cloak
                     x-transition.duration.200ms
                     class="ml-11 mt-1 space-y-0.5">
                    <a href="{{ route('productos.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-hamburger text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Productos</span>
                    </a>
                    <a href="{{ route('inventario.index') }}" 
                       class="flex items-center justify-between px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <div class="flex items-center gap-3">
                            <i class="w-4 text-xs fas fa-boxes text-white/50 group-hover:text-white/80"></i>
                            <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Inventario</span>
                        </div>
                        @php
                            try {
                                $stockBajo = \App\Models\Inventario::whereRaw('cantidad <= stock_minimo')->count();
                            } catch (\Exception $e) {
                                $stockBajo = 0;
                            }
                        @endphp
                        @if($stockBajo > 0)
                            <span x-show="$store.sidebar.sidebarOpen" 
                                  class="px-2 py-1 text-xs font-bold text-white rounded-full bg-red-500/90">
                                {{ $stockBajo }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
            @endif

            <!-- Mesas -->
            @if(in_array($role, ['admin', 'mesero']))
            <div>
                <button @click="menuMesas = !menuMesas" 
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-3">
                        <i class="w-5 text-lg transition-colors fas fa-chair text-white/70 group-hover:text-white"></i>
                        <span x-show="$store.sidebar.sidebarOpen" 
                              class="text-sm font-medium text-white/70 group-hover:text-white whitespace-nowrap">
                            Gestión de Mesas
                        </span>
                    </div>
                    <i x-show="$store.sidebar.sidebarOpen" 
                       :class="menuMesas ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="text-xs transition-transform duration-200 fas text-white/40"></i>
                </button>
                
                <div x-show="menuMesas" 
                     x-cloak
                     x-transition.duration.200ms
                     class="mt-1 ml-11">
                    <a href="{{ route('mesas.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-table text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Mesas</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Operaciones -->
            @if(in_array($role, ['admin', 'cajero']))
            <div>
                <button @click="menuOperaciones = !menuOperaciones" 
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-3">
                        <i class="w-5 text-lg transition-colors fas fa-cash-register text-white/70 group-hover:text-white"></i>
                        <span x-show="$store.sidebar.sidebarOpen" 
                              class="text-sm font-medium text-white/70 group-hover:text-white whitespace-nowrap">
                            Operaciones
                        </span>
                    </div>
                    <i x-show="$store.sidebar.sidebarOpen" 
                       :class="menuOperaciones ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="text-xs transition-transform duration-200 fas text-white/40"></i>
                </button>
                
                <div x-show="menuOperaciones" 
                     x-cloak
                     x-transition.duration.200ms
                     class="ml-11 mt-1 space-y-0.5">
                    <a href="{{ route('pedidos.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-clipboard-list text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Pedidos</span>
                    </a>
                    <a href="{{ route('comandas.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-receipt text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Comandas</span>
                    </a>
                    <a href="{{ route('delivery.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-motorcycle text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Delivery</span>
                    </a>
                    
                    <div class="h-px my-2 bg-white/10"></div>
                    
                    <a href="{{ route('facturas.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-file-invoice text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Pre-factura</span>
                    </a>
                    <a href="{{ route('pagos.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-credit-card text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Pagos</span>
                    </a>
                    <a href="{{ route('cierres.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-cash-register text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Cierre de Caja</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Administración -->
            @if($role == 'admin')
            <div>
                <button @click="menuAdministracion = !menuAdministracion" 
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 group">
                    <div class="flex items-center gap-3">
                        <i class="w-5 text-lg transition-colors fas fa-user-shield text-white/70 group-hover:text-white"></i>
                        <span x-show="$store.sidebar.sidebarOpen" 
                              class="text-sm font-medium text-white/70 group-hover:text-white whitespace-nowrap">
                            Administración
                        </span>
                    </div>
                    <i x-show="$store.sidebar.sidebarOpen" 
                       :class="menuAdministracion ? 'fa-chevron-up' : 'fa-chevron-down'" 
                       class="text-xs transition-transform duration-200 fas text-white/40"></i>
                </button>
                
                <div x-show="menuAdministracion" 
                     x-cloak
                     x-transition.duration.200ms
                     class="mt-1 ml-11">
                    <a href="{{ route('usuarios.index') }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm transition-all duration-200 rounded-lg text-white/60 hover:bg-white/10 hover:text-white group">
                        <i class="w-4 text-xs fas fa-users text-white/50 group-hover:text-white/80"></i>
                        <span x-show="$store.sidebar.sidebarOpen" class="whitespace-nowrap">Usuarios</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Versión -->
            <div class="pt-4 mt-4 border-t border-white/10">
                <div x-show="$store.sidebar.sidebarOpen" class="px-4 py-2">
                    <p class="text-xs text-white/40">v2.0.0</p>
                </div>
            </div>

        </div>
    </nav>
</aside>

<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('sidebar')) {
            Alpine.store('sidebar', {
                sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
                toggle() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sidebarOpen', this.sidebarOpen);
                }
            })
        }
    })
</script>

<style>
    /* Scrollbar personalizada */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.25);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }
    
    [x-cloak] {
        display: none !important;
    }
</style>
