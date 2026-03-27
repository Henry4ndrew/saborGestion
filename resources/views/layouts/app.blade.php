
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaborGestion - {{ $title ?? 'Sistema de Gestión' }}</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar Superior con Perfil y Logout -->
            <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
                <div class="px-6 py-3 flex items-center justify-between">

                    <!-- IZQUIERDA: Usuario y Toggle -->
                    <div class="flex items-center gap-3">
                        <!-- Botón Hamburguesa para Móvil -->
                        <button @click="$store.sidebar.toggle()" 
                                class="p-2 -ml-2 mr-1 transition-colors rounded-lg text-gray-500 hover:bg-gray-100 lg:hidden">
                            <i class="fas fa-bars text-lg"></i>
                        </button>

                        <div class="w-8 h-8 rounded-lg bg-primary bg-opacity-10 flex items-center justify-center">
                            <i class="fas fa-user-alt text-primary text-sm"></i>
                        </div>

                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-700">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ ucfirst(Auth::user()->role) }}
                            </p>
                        </div>
                    </div>

                    <!-- DERECHA: Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt text-red-500"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>

                </div>
            </nav>
            
            <!-- Contenido Principal -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    @if(isset($breadcrumbs))
                        <!-- Breadcrumbs opcional -->
                        <div class="mb-4">
                            <nav class="flex items-center gap-2 text-sm">
                                @foreach($breadcrumbs as $crumb)
                                    @if(!$loop->last)
                                        <a href="{{ $crumb['url'] }}" class="text-gray-500 hover:text-primary transition-colors">
                                            {{ $crumb['label'] }}
                                        </a>
                                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                    @else
                                        <span class="text-gray-800 font-medium">{{ $crumb['label'] }}</span>
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
</body>
</html>