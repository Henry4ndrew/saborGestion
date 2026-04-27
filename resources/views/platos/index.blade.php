{{-- resources/views/platos/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-primary">Productos</h1>
        <a href="{{ route('platos.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nuevo Producto
        </a>
    </div>

    <!-- Panel de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-primary to-secondary rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Productos</p>
                    <p class="text-2xl font-bold">{{ $totalPlatos }}</p>
                </div>
                <i class="fas fa-utensils text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Disponibles</p>
                    <p class="text-2xl font-bold">{{ $platos->where('disponible', true)->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-600 to-red-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">No Disponibles</p>
                    <p class="text-2xl font-bold">{{ $platos->where('disponible', false)->count() }}</p>
                </div>
                <i class="fas fa-times-circle text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Categorías</p>
                    <p class="text-2xl font-bold">{{ $categorias->count() }}</p>
                </div>
                <i class="fas fa-tags text-3xl opacity-80"></i>
            </div>
        </div>
    </div>
    
    <!-- Panel de Filtros -->
    <div class="card p-4">
        <form method="GET" action="{{ route('platos.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-search mr-1 text-primary"></i> Buscar
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nombre del plato..."
                           class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-tag mr-1 text-primary"></i> Categoría
                    </label>
                    <select name="categoria" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-check-circle mr-1 text-green-500"></i> Disponibilidad
                    </label>
                    <select name="disponible" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                        <option value="">Todos</option>
                        <option value="true" {{ request('disponible') == 'true' ? 'selected' : '' }}>Disponibles</option>
                        <option value="false" {{ request('disponible') == 'false' ? 'selected' : '' }}>No Disponibles</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">
                        <i class="fas fa-star mr-1 text-yellow-500"></i> Calificación
                    </label>
                    <select name="score" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                        <option value="">Todas</option>
                        <option value="5" {{ request('score') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 estrellas</option>
                        <option value="4" {{ request('score') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4+ estrellas</option>
                        <option value="3" {{ request('score') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3+ estrellas</option>
                        <option value="alta" {{ request('score') == 'alta' ? 'selected' : '' }}>Alta (4-5)</option>
                        <option value="media" {{ request('score') == 'media' ? 'selected' : '' }}>Media (2-3)</option>
                        <option value="baja" {{ request('score') == 'baja' ? 'selected' : '' }}>Baja (0-1)</option>
                    </select>
                </div>
            </div>

            @if(request()->anyFilled(['search', 'categoria', 'disponible', 'score']))
                <div class="flex justify-end mt-4">
                    <a href="{{ route('platos.index') }}" class="btn-secondary px-6">
                        <i class="fas fa-undo-alt mr-2"></i> Limpiar filtros
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Tabla de Platos -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-border bg-background">
                        <th class="text-left py-3 px-4 font-semibold text-text">Imagen</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Nombre</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Categoría</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Precio</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Calificación</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Estado</th>
                        <th class="text-left py-3 px-4 font-semibold text-text">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($platos as $plato)
                    <tr class="border-b border-border hover:bg-background transition-colors">
                        <td class="py-3 px-4">
                            @if($plato->imagen)
                                <img src="{{ Storage::url($plato->imagen) }}" alt="{{ $plato->nombre }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="font-medium text-text">{{ $plato->nombre }}</p>
                                @if($plato->descripcion)
                                    <p class="text-xs text-muted mt-1">{{ Str::limit($plato->descripcion, 50) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
    <span class="px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
        <i class="fas {{ $plato->categoria->icono }} mr-1"></i> 
        {{ $plato->categoria->nombre }}
    </span>
</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-primary">Bs {{ number_format($plato->precio, 2) }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $plato->score)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-sm"></i>
                                    @endif
                                @endfor
                                <span class="ml-2 text-xs text-muted">({{ number_format($plato->score, 1) }})</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <button class="toggle-disponible" data-id="{{ $plato->id }}">
                                @if($plato->disponible)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Disponible
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> No disponible
                                    </span>
                                @endif
                            </button>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('platos.edit', $plato) }}" class="text-primary hover:text-secondary mr-3 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('platos.destroy', $plato) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('¿Estás seguro de eliminar este plato?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-muted">
                            <i class="fas fa-utensils text-4xl mb-3 block"></i>
                            <p>No hay platos registrados</p>
                            <a href="{{ route('platos.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Agregar primer plato
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $platos->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.toggle-disponible').forEach(button => {
        button.addEventListener('click', function() {
            const platoId = this.dataset.id;
            fetch(`/platos/${platoId}/toggle-disponible`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });
</script>
@endpush
@endsection