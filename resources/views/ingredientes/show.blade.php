{{-- resources/views/ingredientes/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            @if($ingrediente->foto)
                <img src="{{ Storage::url($ingrediente->foto) }}" alt="{{ $ingrediente->nombre }}" class="w-16 h-16 object-cover rounded-full">
            @else
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-carrot text-primary text-2xl"></i>
                </div>
            @endif
            <h1 class="text-3xl font-bold text-primary">{{ $ingrediente->nombre }}</h1>
        </div>
        <div class="space-x-3">
            <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('ingredientes.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información del ingrediente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Platos que usan este ingrediente -->
            <div class="card">
                <h2 class="text-xl font-semibold text-text mb-4">Platos que usan este ingrediente</h2>
                
                @if($ingrediente->platos && $ingrediente->platos->count() > 0)
                    <div class="space-y-3">
                        @foreach($ingrediente->platos as $plato)
                        <div class="border border-border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($plato->imagen)
                                        <img src="{{ Storage::url($plato->imagen) }}" alt="{{ $plato->nombre }}" class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium text-text">{{ $plato->nombre }}</h3>
                                        <p class="text-sm text-muted">
                                            Cantidad: {{ $plato->pivot->cantidad }} {{ $ingrediente->unidad_medida }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-primary font-semibold">${{ number_format($plato->precio, 2) }}</p>
                                    <div class="flex items-center space-x-1 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $plato->score ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-muted">
                        <i class="fas fa-carrot text-4xl mb-3 block"></i>
                        <p>Este ingrediente no se usa en ningún plato actualmente</p>
                        <a href="{{ route('platos.create') }}" class="text-primary hover:text-secondary mt-2 inline-block">
                            <i class="fas fa-plus mr-1"></i> Crear plato con este ingrediente
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
                        <span class="text-sm text-muted">Unidad de medida:</span>
                        <p class="font-medium mt-1">
                            @switch($ingrediente->unidad_medida)
                                @case('gr') Gramos (gr) @break
                                @case('ml') Mililitros (ml) @break
                                @case('unidad') Unidad(es) @break
                                @case('cda') Cucharada(s) @break
                                @case('cdta') Cucharadita(s) @break
                            @endswitch
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Usado en platos:</span>
                        <p class="text-2xl font-bold text-primary mt-1">{{ $ingrediente->platos->count() }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Fecha de creación:</span>
                        <p class="text-text">{{ $ingrediente->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-muted">Última actualización:</span>
                        <p class="text-text">{{ $ingrediente->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection