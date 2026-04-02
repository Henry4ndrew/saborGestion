<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaborGestion - {{ $title ?? 'Sistema de Gestión' }}</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 overflow-hidden">
    <div x-data="appLayout()" 
         x-init="init()"
         class="relative h-screen overflow-hidden">
        
        <!-- Overlay para móvil cuando sidebar está abierto -->
        <div x-show="mobileSidebarOpen"
             x-transition.opacity.duration.300
             @click="closeMobileSidebar()"
             class="fixed inset-0 z-20 bg-black/50 lg:hidden"
             style="display: none;">
        </div>
        
        <div class="flex h-full">
            <!-- Sidebar -->
            <div x-show="sidebarOpen || (window.innerWidth >= 1024 && !mobileSidebarOpen)"
                 x-transition:enter="transition-transform duration-300 ease-in-out"
                 x-transition:enter-start="-translate-x-full lg:translate-x-0"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition-transform duration-300 ease-in-out"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full lg:translate-x-0"
                 class="fixed inset-y-0 left-0 z-30 lg:relative lg:z-0"
                 :class="{
                     'w-72': sidebarExpanded,
                     'w-20': !sidebarExpanded && window.innerWidth >= 1024,
                     'w-72': mobileSidebarOpen && window.innerWidth < 1024
                 }">
                @include('layouts.sidebar')
            </div>
            
            <!-- Contenido Principal -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden"
                 :class="{
                     'lg:ml-0': true,
                     'ml-0': true
                 }">
                
                <!-- Navbar Superior con Perfil y Logout -->
                <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
                    <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
                        <!-- Botón hamburguesa para móvil -->
                        <button @click="toggleMobileSidebar()"
                                class="p-2 -ml-2 rounded-lg lg:hidden hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bars text-gray-600 text-xl"></i>
                        </button>

                        <!-- IZQUIERDA: Usuario -->
                        <div class="flex items-center gap-3 ml-auto lg:ml-0">
                            <div class="w-8 h-8 rounded-lg bg-primary bg-opacity-10 flex items-center justify-center">
                                <i class="fas fa-user-alt text-primary text-sm"></i>
                            </div>

                            <div class="text-left hidden xs:block">
                                <p class="text-sm font-medium text-gray-700">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ ucfirst(Auth::user()->role) }}
                                </p>
                            </div>
                        </div>

                        <!-- DERECHA: Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center gap-2 px-2 sm:px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                                <span class="hidden sm:inline">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </nav>
                
                <!-- Contenido Principal -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
                        @if(isset($breadcrumbs))
                            <!-- Breadcrumbs opcional -->
                            <div class="mb-4">
                                <nav class="flex items-center gap-2 text-sm overflow-x-auto">
                                    @foreach($breadcrumbs as $crumb)
                                        @if(!$loop->last)
                                            <a href="{{ $crumb['url'] }}" class="text-gray-500 hover:text-primary transition-colors whitespace-nowrap">
                                                {{ $crumb['label'] }}
                                            </a>
                                            <i class="fas fa-chevron-right text-xs text-gray-400 flex-shrink-0"></i>
                                        @else
                                            <span class="text-gray-800 font-medium whitespace-nowrap">{{ $crumb['label'] }}</span>
                                        @endif
                                    @endforeach
                                </nav>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    
    <script>
        function appLayout() {
            return {
                sidebarExpanded: localStorage.getItem('sidebarExpanded') !== 'false',
                mobileSidebarOpen: false,
                windowWidth: window.innerWidth,
                
                init() {
                    this.windowWidth = window.innerWidth;
                    window.addEventListener('resize', () => {
                        this.windowWidth = window.innerWidth;
                        if (this.windowWidth >= 1024) {
                            this.mobileSidebarOpen = false;
                        }
                    });
                },
                
                get sidebarOpen() {
                    if (this.windowWidth >= 1024) {
                        return true;
                    }
                    return this.mobileSidebarOpen;
                },
                
                toggleSidebar() {
                    if (this.windowWidth >= 1024) {
                        this.sidebarExpanded = !this.sidebarExpanded;
                        localStorage.setItem('sidebarExpanded', this.sidebarExpanded);
                    }
                },
                
                toggleMobileSidebar() {
                    this.mobileSidebarOpen = !this.mobileSidebarOpen;
                },
                
                closeMobileSidebar() {
                    this.mobileSidebarOpen = false;
                }
            }
        }
    </script>
</body>
</html>