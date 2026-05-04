{{-- resources/views/ingredientes/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ showImageModal: false, modalImageUrl: '', modalImageTitle: '' }">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            @if($ingrediente->foto)
                <img src="{{ Storage::url($ingrediente->foto) }}" 
                     alt="{{ $ingrediente->nombre }}" 
                     class="w-16 h-16 object-cover rounded-full cursor-pointer hover:scale-105 transition-transform"
                     @click="modalImageUrl = '{{ Storage::url($ingrediente->foto) }}'; modalImageTitle = '{{ $ingrediente->nombre }}'; showImageModal = true">
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
                                        <img src="{{ Storage::url($plato->imagen) }}" 
                                             alt="{{ $plato->nombre }}" 
                                             class="w-12 h-12 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                             @click="modalImageUrl = '{{ Storage::url($plato->imagen) }}'; modalImageTitle = '{{ $plato->nombre }}'; showImageModal = true">
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

    <!-- Modal de Imagen -->
    <div x-show="showImageModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75"
         x-cloak
         @keydown.escape.window="showImageModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative max-w-4xl w-full bg-white rounded-xl overflow-hidden shadow-2xl"
             @click.away="showImageModal = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="flex items-center justify-between p-4 border-b border-border">
                <h3 class="text-xl font-bold text-text" x-text="modalImageTitle"></h3>
                <button @click="showImageModal = false" class="text-muted hover:text-text transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="p-2 bg-gray-50 flex justify-center items-center">
                <img :src="modalImageUrl" :alt="modalImageTitle" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-inner">
            </div>
            
            <div class="p-4 flex justify-end">
                <button @click="showImageModal = false" class="btn-secondary">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection