{{-- resources/views/platos/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Editar Plato: {{ $plato->nombre }}</h1>
        <a href="{{ route('platos.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="card">
        <form action="{{ route('platos.update', $plato) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Información básica -->
                <div>
                    <h2 class="text-xl font-semibold text-text mb-4">Información del Plato</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Nombre *</label>
                            <input type="text" name="nombre" required value="{{ old('nombre', $plato->nombre) }}" class="w-full px-4 py-2 rounded-lg border border-border">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Precio *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="precio" step="0.01" required value="{{ old('precio', $plato->precio) }}" class="w-full pl-8 pr-4 py-2 rounded-lg border border-border">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Categoría *</label>
                            <div class="flex gap-2">
                                <select name="categoria_id" required class="flex-1 px-4 py-2 rounded-lg border border-border">
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $plato->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openCategoriaModal()" class="btn-secondary px-4">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Imagen Actual</label>
                            @if($plato->imagen)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ Storage::url($plato->imagen) }}" alt="{{ $plato->nombre }}" class="w-20 h-20 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <input type="file" name="imagen" accept="image/*" class="w-full">
                                        <p class="text-xs text-muted mt-1">Dejar vacío para mantener la imagen actual</p>
                                    </div>
                                </div>
                            @else
                                <input type="file" name="imagen" accept="image/*" class="w-full">
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 rounded-lg border border-border">{{ old('descripcion', $plato->descripcion) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="disponible" value="1" {{ $plato->disponible ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm text-text">Disponible para la venta</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Ingredientes -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-text">Ingredientes</h2>
                        <button type="button" onclick="openIngredienteModal()" class="btn-secondary text-sm">
                            <i class="fas fa-plus mr-1"></i> Nuevo Ingrediente
                        </button>
                    </div>
                    
                    <div id="ingredientes-container" class="space-y-3">
                        @foreach($plato->ingredientes as $index => $ingrediente)
                        <div class="flex gap-3 items-start border border-border rounded-lg p-3 bg-background">
                            <select name="ingredientes[{{ $index }}][id]" class="flex-1 px-4 py-2 rounded-lg border border-border" required>
                                @foreach($ingredientes as $ing)
                                    <option value="{{ $ing->id }}" {{ $ingrediente->id == $ing->id ? 'selected' : '' }}>
                                        {{ $ing->nombre }} ({{ $ing->unidad_medida }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="ingredientes[{{ $index }}][cantidad]" value="{{ $ingrediente->pivot->cantidad }}" placeholder="Cantidad" step="0.01" required class="w-32 px-4 py-2 rounded-lg border border-border">
                            <button type="button" onclick="this.closest('.flex').remove()" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" onclick="agregarIngrediente()" class="mt-3 text-primary hover:text-secondary">
                        <i class="fas fa-plus-circle mr-1"></i> Agregar ingrediente
                    </button>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="submit" class="btn-primary px-6">
                        <i class="fas fa-save mr-2"></i> Actualizar Plato
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let ingredienteIndex = {{ $plato->ingredientes->count() }};

function agregarIngrediente() {
    const ingredientesExistentes = @json($ingredientes);
    
    let html = `
        <div class="flex gap-3 items-start border border-border rounded-lg p-3 bg-background">
            <select name="ingredientes[${ingredienteIndex}][id]" class="flex-1 px-4 py-2 rounded-lg border border-border" required>
                <option value="">Seleccionar ingrediente</option>
                ${ingredientesExistentes.map(ing => `<option value="${ing.id}">${ing.nombre} (${ing.unidad_medida})</option>`).join('')}
            </select>
            <input type="number" name="ingredientes[${ingredienteIndex}][cantidad]" placeholder="Cantidad" step="0.01" required class="w-32 px-4 py-2 rounded-lg border border-border">
            <button type="button" onclick="this.closest('.flex').remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    const container = document.getElementById('ingredientes-container');
    container.insertAdjacentHTML('beforeend', html);
    ingredienteIndex++;
}

// Las mismas funciones de modal del create.blade.php
function openCategoriaModal() { /* ... */ }
function closeCategoriaModal() { /* ... */ }
function openIngredienteModal() { /* ... */ }
function closeIngredienteModal() { /* ... */ }
</script>
@endpush
@endsection