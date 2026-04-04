{{-- resources/views/ingredientes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary">Ingredientes</h1>
        <a href="{{ route('ingredientes.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nuevo Ingrediente
        </a>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-primary to-secondary rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Ingredientes</p>
                    <p class="text-2xl font-bold">{{ $totalIngredientes }}</p>
                </div>
                <i class="fas fa-carrot text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">En uso en platos</p>
                    <p class="text-2xl font-bold">{{ $ingredientes->where('platos_count', '>', 0)->count() }}</p>
                </div>
                <i class="fas fa-utensils text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Sin usar</p>
                    <p class="text-2xl font-bold">{{ $ingredientes->where('platos_count', 0)->count() }}</p>
                </div>
                <i class="fas fa-box-open text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Unidades diferentes</p>
                    <p class="text-2xl font-bold">{{ $ingredientes->groupBy('unidad_medida')->count() }}</p>
                </div>
                <i class="fas fa-ruler text-3xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Panel de búsqueda y filtros -->
    <div class="card p-4">
        <form method="GET" action="{{ route('ingredientes.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-search mr-1 text-primary"></i> Buscar
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nombre del ingrediente..."
                               class="w-full px-4 py-2 pl-10 rounded-lg border border-border focus:outline-none focus:border-primary">
                        <i class="fas fa-search absolute left-3 top-3 text-muted"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-ruler mr-1 text-primary"></i> Unidad de Medida
                    </label>
                    <div class="relative">
                        <select name="unidad" class="w-full px-4 py-2 appearance-none rounded-lg border border-border focus:outline-none focus:border-primary">
                            <option value="">Todas</option>
                            <option value="gr" {{ request('unidad') == 'gr' ? 'selected' : '' }}>Gramos (gr)</option>
                            <option value="ml" {{ request('unidad') == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                            <option value="unidad" {{ request('unidad') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                            <option value="cda" {{ request('unidad') == 'cda' ? 'selected' : '' }}>Cucharada (cda)</option>
                            <option value="cdta" {{ request('unidad') == 'cdta' ? 'selected' : '' }}>Cucharadita (cdta)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-muted pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>
            </div>

            @if(request()->anyFilled(['search', 'unidad']))
                <div class="flex justify-end mt-4">
                    <a href="{{ route('ingredientes.index') }}" class="btn-secondary px-6">
                        <i class="fas fa-undo-alt mr-2"></i> Limpiar filtros
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Resultados de búsqueda -->
    <div class="flex justify-between items-center mb-2">
        <p class="text-sm text-muted">
            <i class="fas fa-chart-line mr-1"></i> 
            Mostrando {{ $ingredientes->count() }} de {{ $totalIngredientes }} ingredientes
            @if(request('search'))
                <span class="ml-2 px-2 py-1 bg-primary/10 rounded-full text-xs">
                    <i class="fas fa-search mr-1"></i> Búsqueda: "{{ request('search') }}"
                </span>
            @endif
            @if(request('unidad'))
                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                    <i class="fas fa-ruler mr-1"></i> Unidad: {{ request('unidad') }}
                </span>
            @endif
        </p>
    </div>

    <!-- Tabla de Ingredientes -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-border bg-background">
                        <th class="text-left py-3 px-4 font-semibold text-text">Foto</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Nombre</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Unidad</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Usado en</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredientes as $ingrediente)
                    <tr class="border-b border-border hover:bg-background transition-colors">
                        <td class="py-3 px-4">
                            @if($ingrediente->foto)
                                <img src="{{ Storage::url($ingrediente->foto) }}" 
                                     alt="{{ $ingrediente->nombre }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-carrot text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-text">{{ $ingrediente->nombre }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                @switch($ingrediente->unidad_medida)
                                    @case('gr') Gramos @break
                                    @case('ml') Mililitros @break
                                    @case('unidad') Unidad(es) @break
                                    @case('cda') Cucharada(s) @break
                                    @case('cdta') Cucharadita(s) @break
                                @endswitch
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ingrediente->platos_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                <i class="fas fa-utensils mr-1"></i>
                                {{ $ingrediente->platos_count }} plato(s)
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('ingredientes.show', $ingrediente) }}" class="text-info hover:text-info-dark mr-2 transition-colors" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="text-primary hover:text-secondary mr-2 transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('ingredientes.destroy', $ingrediente) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" 
                                        onclick="return confirm('¿Estás seguro de eliminar este ingrediente?')"
                                        {{ $ingrediente->platos_count > 0 ? 'disabled title="No se puede eliminar porque está siendo usado en platos"' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-muted">
                            <i class="fas fa-carrot text-4xl mb-3 block"></i>
                            <p>No hay ingredientes registrados</p>
                            <a href="{{ route('ingredientes.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Crear primer ingrediente
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $ingredientes->links() }}
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
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@push('scripts')
<script>
    // Auto-submit para filtros
    const unidadFilter = document.querySelector('select[name="unidad"]');
    const filterForm = document.getElementById('filterForm');
    
    if (unidadFilter) {
        unidadFilter.addEventListener('change', function() {
            filterForm.submit();
        });
    }
</script>
@endpush
@endsection