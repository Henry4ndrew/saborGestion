{{-- resources/views/platos/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Crear Nuevo Plato</h1>
        <a href="{{ route('platos.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="card">
        <form action="{{ route('platos.store') }}" method="POST" enctype="multipart/form-data" id="platoForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Información básica -->
                <div>
                    <h2 class="text-xl font-semibold text-text mb-4">Información del Plato</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Nombre *</label>
                            <input type="text" name="nombre" required value="{{ old('nombre') }}" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Precio *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="precio" step="0.01" required value="{{ old('precio') }}" class="w-full pl-8 pr-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                            </div>
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Categoría *</label>
                            <div class="flex gap-2">
                                <select name="categoria_id" required class="flex-1 px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('categorias.create') }}" 
                                   target="_blank" 
                                   class="btn-secondary px-4 inline-flex items-center justify-center">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <p class="text-xs text-muted mt-1">
                                <i class="fas fa-info-circle"></i> 
                                ¿No encuentras la categoría? 
                                <a href="{{ route('categorias.create') }}" target="_blank" class="text-primary">Créala aquí</a> 
                                y actualiza la página
                            </p>
                            @error('categoria_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Imagen</label>
                            <input type="file" name="imagen" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                            @error('imagen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">{{ old('descripcion') }}</textarea>
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="disponible" value="1" checked class="mr-2">
                                <span class="text-sm text-text">Disponible para la venta</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Ingredientes -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-text">Ingredientes</h2>
                        <a href="{{ route('ingredientes.create') }}" 
                           target="_blank" 
                           class="btn-secondary text-sm inline-flex items-center justify-center">
                            <i class="fas fa-plus mr-1"></i> Nuevo Ingrediente
                        </a>
                    </div>
                    
                    <div id="ingredientes-container" class="space-y-3">
                        <div class="text-center text-muted py-4" id="no-ingredientes-msg">
                            <i class="fas fa-info-circle"></i> No hay ingredientes agregados
                        </div>
                    </div>
                    
                    <button type="button" onclick="agregarIngrediente()" class="mt-3 text-primary hover:text-secondary">
                        <i class="fas fa-plus-circle mr-1"></i> Agregar ingrediente existente
                    </button>
                    
                    <p class="text-xs text-muted mt-2">
                        <i class="fas fa-info-circle"></i> 
                        ¿No encuentras el ingrediente? 
                        <a href="{{ route('ingredientes.create') }}" target="_blank" class="text-primary">Créalo aquí</a> 
                        y actualiza la página
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="submit" class="btn-primary px-6">
                        <i class="fas fa-save mr-2"></i> Guardar Plato
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function agregarIngrediente() {
    const ingredientesExistentes = @json($ingredientes);
    
    if (ingredientesExistentes.length === 0) {
        alert('No hay ingredientes disponibles. Crea un ingrediente primero.');
        return;
    }
    
    let html = `
        <div class="flex gap-3 items-start border border-border rounded-lg p-3 bg-background">
            <select name="ingredientes[__index__][id]" class="flex-1 px-4 py-2 rounded-lg border border-border ingrediente-select" required>
                <option value="">Seleccionar ingrediente</option>
                ${ingredientesExistentes.map(ing => `<option value="${ing.id}">${ing.nombre} (${ing.unidad_medida})</option>`).join('')}
            </select>
            <input type="number" name="ingredientes[__index__][cantidad]" placeholder="Cantidad" step="0.01" required class="w-32 px-4 py-2 rounded-lg border border-border">
            <button type="button" onclick="this.closest('.flex').remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    const container = document.getElementById('ingredientes-container');
    const index = Date.now();
    html = html.replace(/__index__/g, index);
    
    if (container.querySelector('#no-ingredientes-msg')) {
        container.innerHTML = '';
    }
    
    container.insertAdjacentHTML('beforeend', html);
}

// Mensaje para recargar cuando la ventana vuelve a tener foco
window.addEventListener('focus', function() {
    // Verificar si hay nuevos datos (opcional)
    fetch('{{ route("categorias.index") }}', { method: 'HEAD' })
        .catch(() => {});
});

// Instrucción para el usuario
console.log('Consejo: Para agregar nuevas categorías o ingredientes, ábrelos en nueva pestaña y recarga esta página');
</script>
@endpush
@endsection