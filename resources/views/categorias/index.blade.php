{{-- resources/views/categorias/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary">Categorías</h1>
        <a href="{{ route('categorias.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nueva Categoría
        </a>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-primary to-secondary rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Categorías</p>
                    <p class="text-2xl font-bold">{{ $categorias->count() }}</p>
                </div>
                <i class="fas fa-tags text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Activas</p>
                    <p class="text-2xl font-bold">{{ $categorias->where('activo', true)->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-600 to-red-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Inactivas</p>
                    <p class="text-2xl font-bold">{{ $categorias->where('activo', false)->count() }}</p>
                </div>
                <i class="fas fa-times-circle text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Platos</p>
                    <p class="text-2xl font-bold">{{ $categorias->sum('platos_count') }}</p>
                </div>
                <i class="fas fa-utensils text-3xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Tabla de Categorías -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-border bg-background">
                        <th class="text-left py-3 px-4 font-semibold text-text">Icono</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Nombre</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Descripción</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Platos</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Estado</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr class="border-b border-border hover:bg-background transition-colors">
                        <td class="py-3 px-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="fas {{ $categoria->icono ?? 'fa-tag' }} text-primary text-lg"></i>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-text">{{ $categoria->nombre }}</span>
                        </td>
                        <td class="py-3 px-4 text-text">
                            {{ Str::limit($categoria->descripcion, 50) ?: '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                <i class="fas fa-utensils mr-1"></i> {{ $categoria->platos_count }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('categorias.toggle-activo', $categoria) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="toggle-activo-btn">
                                    @if($categoria->activo)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                            <i class="fas fa-check-circle mr-1"></i> Activa
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                            <i class="fas fa-times-circle mr-1"></i> Inactiva
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('categorias.show', $categoria) }}" class="text-info hover:text-info-dark mr-2 transition-colors" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('categorias.edit', $categoria) }}" class="text-primary hover:text-secondary mr-2 transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" 
                                        onclick="return confirm('¿Estás seguro de eliminar esta categoría?')"
                                        {{ $categoria->platos_count > 0 ? 'disabled title="No se puede eliminar porque tiene platos asociados"' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                         </td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-muted">
                            <i class="fas fa-tags text-4xl mb-3 block"></i>
                            <p>No hay categorías registradas</p>
                            <a href="{{ route('categorias.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Crear primera categoría
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-info {
        color: #17a2b8;
    }
    .text-info-dark {
        color: #0f6674;
    }
    .toggle-activo-btn {
        cursor: pointer;
    }
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection