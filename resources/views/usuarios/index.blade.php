@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Administradores -->
        <div class="bg-gradient-to-br from-primary to-secondary rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Administradores</p>
                    <p class="text-2xl font-bold">{{ $usuarios->where('role', 'admin')->count() }}</p>
                </div>
                <i class="fas fa-shield-alt text-3xl opacity-80"></i>
            </div>
        </div>
        
        <!-- Meseros -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Meseros</p>
                    <p class="text-2xl font-bold">{{ $usuarios->where('role', 'mesero')->count() }}</p>
                </div>
                <i class="fas fa-concierge-bell text-3xl opacity-80"></i>
            </div>
        </div>
        
        <!-- Cocineros -->
        <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Cocineros</p>
                    <p class="text-2xl font-bold">{{ $usuarios->where('role', 'cocinero')->count() }}</p>
                </div>
                <i class="fas fa-utensils text-3xl opacity-80"></i>
            </div>
        </div>
        
        <!-- Cajeros -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Cajeros</p>
                    <p class="text-2xl font-bold">{{ $usuarios->where('role', 'cajero')->count() }}</p>
                </div>
                <i class="fas fa-cash-register text-3xl opacity-80"></i>
            </div>
        </div>

        <!-- Clientes -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Clientes</p>
                    <p class="text-2xl font-bold">{{ $usuarios->where('role', 'cliente')->count() }}</p>
                </div>
                <i class="fas fa-users text-3xl opacity-80"></i>
            </div>
        </div>
    </div>
    
    <!-- Panel de Búsqueda y Filtros -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('usuarios.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Buscador inteligente -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-search mr-1 text-primary"></i> Buscar por nombre o email
                    </label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Ej: José Pérez, juan@email.com..."
                                   class="w-full px-4 py-2 pl-10 pr-10 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   id="searchInput">
                            <i class="fas fa-search absolute left-3 top-3 text-muted"></i>
                            @if(request('search'))
                                <button type="button" id="clearSearch" class="absolute right-3 top-2 text-muted hover:text-red-500 transition-colors">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            @endif
                        </div>
                        <button type="submit" class="btn-primary px-6">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </div>
                    <p class="text-xs text-muted mt-1">
                        <i class="fas fa-info-circle"></i> No distingue mayúsculas, acentos o espacios
                    </p>
                </div>

                <!-- Filtro por Rol -->
                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-user-tag mr-1 text-primary"></i> Filtrar por Rol
                    </label>
                    <div class="relative">
                        <select name="rol" 
                                id="rolFilter"
                                class="w-full px-4 py-2 pl-10 pr-8 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none bg-white cursor-pointer">
                            <option value="">Todos los roles</option>
                            <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>👑 Administradores</option>
                            <option value="mesero" {{ request('rol') == 'mesero' ? 'selected' : '' }}>🍽️ Meseros</option>
                            <option value="cocinero" {{ request('rol') == 'cocinero' ? 'selected' : '' }}>👨‍🍳 Cocineros</option>
                            <option value="cajero" {{ request('rol') == 'cajero' ? 'selected' : '' }}>💰 Cajeros</option>
                            <option value="cliente" {{ request('rol') == 'cliente' ? 'selected' : '' }}>👥 Clientes</option>
                        </select>
                        <i class="fas fa-users absolute left-3 top-3 text-muted"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-muted pointer-events-none"></i>
                    </div>
                </div>

                <!-- Filtro por Calificación -->
                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-star mr-1 text-yellow-500"></i> Filtrar por Calificación
                    </label>
                    <div class="relative">
                        <select name="calificacion" 
                                id="scoreFilter"
                                class="w-full px-4 py-2 pl-10 pr-8 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none bg-white cursor-pointer">
                            <option value="">Todas las calificaciones</option>
                            <option value="5" {{ request('calificacion') == '5' ? 'selected' : '' }}>⭐ 5 estrellas</option>
                            <option value="4" {{ request('calificacion') == '4' ? 'selected' : '' }}>⭐ 4 estrellas</option>
                            <option value="3" {{ request('calificacion') == '3' ? 'selected' : '' }}>⭐ 3 estrellas</option>
                            <option value="2" {{ request('calificacion') == '2' ? 'selected' : '' }}>⭐ 2 estrellas</option>
                            <option value="1" {{ request('calificacion') == '1' ? 'selected' : '' }}>⭐ 1 estrella</option>
                            <option value="0" {{ request('calificacion') == '0' ? 'selected' : '' }}>☆ Sin calificación</option>
                            <option value="alta" {{ request('calificacion') == 'alta' ? 'selected' : '' }}>🌟 Alta (4-5 estrellas)</option>
                            <option value="media" {{ request('calificacion') == 'media' ? 'selected' : '' }}>⭐ Media (2-3 estrellas)</option>
                            <option value="baja" {{ request('calificacion') == 'baja' ? 'selected' : '' }}>💫 Baja (0-1 estrellas)</option>
                        </select>
                        <i class="fas fa-star absolute left-3 top-3 text-yellow-500"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-muted pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Botones de acción adicionales -->
            @if(request('search') || request('rol') || request('calificacion'))
                <div class="flex justify-end mt-4">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary px-6">
                        <i class="fas fa-undo-alt mr-2"></i> Limpiar todos los filtros
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Resultados de búsqueda -->
    <div class="flex justify-between items-center mb-2">
        <p class="text-sm text-muted">
            <i class="fas fa-chart-line mr-1"></i> 
            Mostrando {{ $usuarios->count() }} de {{ $totalUsuarios }} usuarios
            @if(request('search'))
                <span class="ml-2 px-2 py-1 bg-primary/10 rounded-full text-xs">
                    <i class="fas fa-search mr-1"></i> Búsqueda: "{{ request('search') }}"
                </span>
            @endif
            @if(request('rol'))
                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                    <i class="fas fa-user-tag mr-1"></i> Rol: {{ request('rol') }}
                </span>
            @endif
            @if(request('calificacion'))
                <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                    <i class="fas fa-star mr-1"></i> Calificación: {{ request('calificacion') }}
                </span>
            @endif
        </p>
    </div>

    

   <!-- Pestañas con cambio automático y limpieza de filtros -->
    <div class="card" x-data="{ 
        activeTab: (() => {
            if ('{{ request('rol') }}' === 'cliente') {
                return 'clientes';
            }
            if ('{{ request('rol') }}' === 'admin' || '{{ request('rol') }}' === 'mesero' || '{{ request('rol') }}' === 'cocinero' || '{{ request('rol') }}' === 'cajero') {
                return 'restaurante';
            }
            let savedTab = localStorage.getItem('usuarios_active_tab');
            return (savedTab === 'restaurante' || savedTab === 'clientes') ? savedTab : 'restaurante';
        })(),
        setActiveTab(tab) {
            localStorage.setItem('usuarios_active_tab', tab);
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.delete('rol');
            url.searchParams.delete('calificacion');
            window.location.href = url.toString();
        }
    }">

        <!-- Pestañas -->
        <div class="border-b border-border mb-6">
            <nav class="flex space-x-8">
                <button @click="setActiveTab('restaurante')" 
                        :class="{ 'border-primary text-primary': activeTab === 'restaurante', 'border-transparent text-muted hover:text-text hover:border-border': activeTab !== 'restaurante' }"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    <i class="fas fa-store mr-2"></i>
                    Personal del Restaurante
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-background text-muted">
                        {{ $usuarios->whereIn('role', ['admin', 'mesero', 'cocinero', 'cajero'])->count() }}
                    </span>
                </button>
                
                <button @click="setActiveTab('clientes')" 
                        :class="{ 'border-primary text-primary': activeTab === 'clientes', 'border-transparent text-muted hover:text-text hover:border-border': activeTab !== 'clientes' }"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    <i class="fas fa-users mr-2"></i>
                    Clientes
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-background text-muted">
                        {{ $usuarios->where('role', 'cliente')->count() }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Tabla Personal del Restaurante -->
        <div x-show="activeTab === 'restaurante'" x-cloak>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-border bg-background">
                            <th class="text-left py-3 px-4 font-semibold text-text">Nombre</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Email</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Rol</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Celular</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Calificación</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios->whereIn('role', ['admin', 'mesero', 'cocinero', 'cajero']) as $usuario)
                        <tr class="border-b border-border hover:bg-background transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary bg-opacity-10 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-primary text-sm"></i>
                                    </div>
                                    <span class="font-medium text-text">{{ $usuario->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-text">{{ $usuario->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($usuario->role == 'admin') bg-red-100 text-red-800
                                    @elseif($usuario->role == 'mesero') bg-blue-100 text-blue-800
                                    @elseif($usuario->role == 'cocinero') bg-green-100 text-green-800
                                    @elseif($usuario->role == 'cajero') bg-purple-100 text-purple-800
                                    @endif">
                                    <i class="fas 
                                        @if($usuario->role == 'admin') fa-shield-alt
                                        @elseif($usuario->role == 'mesero') fa-concierge-bell
                                        @elseif($usuario->role == 'cocinero') fa-utensils
                                        @elseif($usuario->role == 'cajero') fa-cash-register
                                        @endif mr-1"></i>
                                    {{ ucfirst($usuario->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($usuario->celular)
                                    <span class="text-sm text-muted">
                                        <i class="fas fa-phone-alt mr-1"></i> {{ $usuario->celular }}
                                    </span>
                                @else
                                    <span class="text-sm text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-1">
                                    @php
                                        $score = $usuario->score ?? 0;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $score)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-sm"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-xs font-medium text-muted">({{ $score }})</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-primary hover:text-secondary mr-3 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-muted">
                                <i class="fas fa-user-friends text-4xl mb-3 block"></i>
                                <p>No hay personal registrado en el restaurante</p>
                                <a href="{{ route('usuarios.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                                    <i class="fas fa-plus mr-1"></i> Agregar primer usuario
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla Clientes -->
        <div x-show="activeTab === 'clientes'" x-cloak>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-border bg-background">
                            <th class="text-left py-3 px-4 font-semibold text-text">Nombre</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Email</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Celular</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Dirección</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Calificación</th>
                            <th class="text-left py-3 px-4 font-semibold text-text">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios->where('role', 'cliente') as $usuario)
                        <tr class="border-b border-border hover:bg-background transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-accent bg-opacity-20 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-primary text-sm"></i>
                                    </div>
                                    <span class="font-medium text-text">{{ $usuario->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-text">{{ $usuario->email }}</td>
                            <td class="py-3 px-4">
                                @if($usuario->celular)
                                    <span class="text-sm text-muted">
                                        <i class="fab fa-whatsapp text-green-500 mr-1"></i> {{ $usuario->celular }}
                                    </span>
                                @else
                                    <span class="text-sm text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($usuario->direccion)
                                    <span class="text-sm text-muted">
                                        <i class="fas fa-map-marker-alt text-primary mr-1"></i> {{ $usuario->direccion }}
                                    </span>
                                @else
                                    <span class="text-sm text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-1">
                                    @php
                                        $score = $usuario->score ?? 0;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $score)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-sm"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-xs font-medium text-muted">({{ $score }})</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-primary hover:text-secondary mr-3 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-muted">
                                <i class="fas fa-user-plus text-4xl mb-3 block"></i>
                                <p>No hay clientes registrados</p>
                                <a href="{{ route('usuarios.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                                    <i class="fas fa-plus mr-1"></i> Registrar primer cliente
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Estilos para botones */
    .btn-secondary {
        @apply bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200 inline-flex items-center;
    }
    
    /* Efecto hover en filas de tabla */
    tbody tr {
        transition: all 0.2s ease;
    }
    
    /* Mejora visual para selects */
    select {
        cursor: pointer;
    }
    
    select:hover {
        border-color: #cbd5e1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Pestaña activa guardada:', localStorage.getItem('usuarios_active_tab'));
        
        // Auto-submit para selects (filtros automáticos)
        const rolFilter = document.getElementById('rolFilter');
        const scoreFilter = document.getElementById('scoreFilter');
        const filterForm = document.getElementById('filterForm');
        
        if (rolFilter) {
            rolFilter.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        if (scoreFilter) {
            scoreFilter.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        // Botón para limpiar búsqueda
        const clearSearchBtn = document.getElementById('clearSearch');
        const searchInput = document.getElementById('searchInput');
        
        if (clearSearchBtn && searchInput) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterForm.submit();
            });
        }
    });
</script>
@endsection