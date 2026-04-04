{{-- resources/views/categorias/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                <i class="fas {{ $categoria->icono ?? 'fa-tag' }} text-primary text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-primary">{{ $categoria->nombre }}</h1>
        </div>
        <div class="space-x-3">
            <a href="{{ route('categorias.edit', $categoria) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('categorias.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información de la categoría -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <h2 class="text-xl font-semibold text-text mb-4">Descripción</h2>
                <p class="text-text">{{ $categoria->descripcion ?? 'No hay descripción disponible' }}</p>
            </div>

            <!-- Platos de esta categoría -->
            <div class="card mt-6">
                <h2 class="text-xl font-semibold text-text mb-4">Platos en esta categoría</h2>
                
                @if($categoria->platos && $categoria->platos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($categoria->platos as $plato)
                        <div class="border border-border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-3">
                                @if($plato->imagen)
                                    <img src="{{ Storage::url($plato->imagen) }}" alt="{{ $plato->nombre }}" class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-utensils text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-medium text-text">{{ $plato->nombre }}</h3>
                                    <p class="text-sm text-primary font-semibold">${{ number_format($plato->precio, 2) }}</p>
                                </div>
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $plato->score ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-muted">
                        <i class="fas fa-utensils text-4xl mb-3 block"></i>
                        <p>No hay platos en esta categoría</p>
                        <a href="{{ route('platos.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                            <i class="fas fa-plus mr-1"></i> Agregar plato
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-text mb-3">Información</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-muted">Estado:</span>
                        <div class="mt-1">
                            @if($categoria->activo)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Activa
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Inactiva
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Total de platos:</span>
                        <p class="text-2xl font-bold text-primary mt-1">{{ $categoria->platos->count() }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Fecha de creación:</span>
                        <p class="text-text">{{ $categoria->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Última actualización:</span>
                        <p class="text-text">{{ $categoria->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection