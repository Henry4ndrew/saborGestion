{{-- resources/views/platos/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Botón volver -->
    <div class="flex justify-between items-center">
        <a href="{{ route('platos.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Productos
        </a>
        
        <div class="space-x-2">
            <a href="{{ route('platos.edit', $plato) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Editar Producto
            </a>
            <form action="{{ route('platos.destroy', $plato) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de eliminar este plato?')">
                    <i class="fas fa-trash mr-2"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Información principal del plato -->
    <div class="card overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Columna de la imagen -->
            <div class="bg-gray-50 rounded-lg flex items-center justify-center p-6">
                @if($plato->imagen)
                    <img src="{{ Storage::url($plato->imagen) }}" 
                         alt="{{ $plato->nombre }}" 
                         class="w-full max-h-96 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex flex-col items-center justify-center">
                        <i class="fas fa-utensils text-gray-400 text-6xl mb-3"></i>
                        <p class="text-gray-500">Sin imagen disponible</p>
                    </div>
                @endif
            </div>
            
            <!-- Columna de la información -->
            <div class="space-y-4">
                <!-- Badge de disponibilidad -->
                <div>
                    @if($plato->disponible)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Disponible
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i> No disponible
                        </span>
                    @endif
                </div>
                
                <!-- Nombre -->
                <h1 class="text-3xl font-bold text-text">{{ $plato->nombre }}</h1>
                
                <!-- Categoría -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tag text-primary"></i>
                    <span class="text-muted">Categoría:</span>
                    <a href="{{ route('platos.index', ['categoria' => $plato->categoria_id]) }}" 
                       class="text-primary hover:text-secondary font-medium">
                        {{ $plato->categoria->nombre }}
                    </a>
                </div>
                
                <!-- Precio -->
                <div class="bg-gradient-to-r from-primary to-secondary rounded-lg p-4 text-white">
                    <p class="text-sm opacity-90">Precio</p>
                    <p class="text-3xl font-bold">Bs {{ number_format($plato->precio, 2)}}</p>
                </div>
                
                <!-- Calificación -->
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $plato->score)
                                <i class="fas fa-star text-yellow-400 text-lg"></i>
                            @else
                                <i class="far fa-star text-gray-300 text-lg"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">({{ number_format($plato->score, 1) }} / 5)</span>
                </div>
                
                <!-- Descripción -->
                @if($plato->descripcion)
                    <div>
                        <h3 class="font-semibold text-text mb-2">
                            <i class="fas fa-align-left mr-2 text-primary"></i> Descripción
                        </h3>
                        <p class="text-muted leading-relaxed">{{ $plato->descripcion }}</p>
                    </div>
                @endif
                
                <!-- Stats rápidos -->
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <div class="text-center p-3 bg-background rounded-lg">
                        <i class="fas fa-carrot text-primary text-xl mb-1"></i>
                        <p class="text-2xl font-bold text-text">{{ $totalIngredientes }}</p>
                        <p class="text-xs text-muted">Ingredientes</p>
                    </div>
                    <div class="text-center p-3 bg-background rounded-lg">
                        <i class="fas fa-calendar-alt text-primary text-xl mb-1"></i>
                        <p class="text-sm font-medium text-text">{{ $plato->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-muted">Fecha creación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de ingredientes -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-text">
                <i class="fas fa-list-ul mr-2 text-primary"></i> Ingredientes
            </h2>
            <span class="text-sm text-muted">{{ $totalIngredientes }} ingredientes</span>
        </div>
        
        @if($plato->ingredientes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($plato->ingredientes as $ingrediente)
                    <div class="flex items-center space-x-3 p-3 border border-border rounded-lg hover:bg-background transition-colors">
                        <!-- Imagen del ingrediente -->
                        <div class="w-12 h-12 flex-shrink-0">
                            @if($ingrediente->foto)
                                <img src="{{ Storage::url($ingrediente->foto) }}" 
                                     alt="{{ $ingrediente->nombre }}"
                                     class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-carrot text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Información del ingrediente -->
                        <div class="flex-1">
                            <p class="font-medium text-text">{{ $ingrediente->nombre }}</p>
                            <p class="text-xs text-muted">Unidad: {{ $ingrediente->unidad_medida }}</p>
                        </div>
                        
                        <!-- Cantidad -->
                        <div class="text-right">
                            <p class="font-semibold text-primary">{{ number_format($ingrediente->pivot->cantidad, 2) }}</p>
                            <p class="text-xs text-muted">{{ $ingrediente->unidad_medida }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-muted">
                <i class="fas fa-info-circle text-4xl mb-3"></i>
                <p>Este plato no tiene ingredientes registrados</p>
            </div>
        @endif
    </div>

    <!-- Productos relacionados -->
    @if($platosRelacionados->count() > 0)
    <div class="card">
        <h2 class="text-xl font-bold text-text mb-4">
            <i class="fas fa-heart mr-2 text-primary"></i> Productos Relacionados
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($platosRelacionados as $platoRelacionado)
                <div class="border border-border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="{{ route('platos.show', $platoRelacionado) }}">
                        @if($platoRelacionado->imagen)
                            <img src="{{ Storage::url($platoRelacionado->imagen) }}" 
                                 alt="{{ $platoRelacionado->nombre }}"
                                 class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        <div class="p-3">
                            <h3 class="font-medium text-text truncate">{{ $platoRelacionado->nombre }}</h3>
                            <p class="text-primary font-bold mt-1">Bs {{ number_format($platoRelacionado->precio, 2) }}</p>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $platoRelacionado->score)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-xs"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection