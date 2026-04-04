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
    
    <!-- Modificado: Agregar persistencia en localStorage -->
    <div class="card" x-data="{ 
        activeTab: localStorage.getItem('usuarios_active_tab') || 'restaurante',
        setActiveTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('usuarios_active_tab', tab);
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
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-accent bg-opacity-20 text-primary">
                                    <i class="fas fa-star mr-1"></i> {{ $usuario->score ?? 0 }} pts
                                </span>
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
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-accent bg-opacity-20 text-primary">
                                    <i class="fas fa-star mr-1"></i> {{ $usuario->score ?? 0 }} pts
                                </span>
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
</style>

<script>
    // Limpiar el almacenamiento cuando el usuario cierra la sesión (opcional)
    document.addEventListener('DOMContentLoaded', function() {
        // Si quieres que la pestaña se reinicie al cerrar sesión, puedes agregar un listener
        // o simplemente dejar que persista entre sesiones
        console.log('Pestaña activa guardada:', localStorage.getItem('usuarios_active_tab'));
    });
</script>
@endsection