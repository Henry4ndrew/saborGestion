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
                                <button type="button" onclick="openCategoriaModal()" class="btn-secondary px-4">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
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
                        <button type="button" onclick="openIngredienteModal()" class="btn-secondary text-sm">
                            <i class="fas fa-plus mr-1"></i> Nuevo Ingrediente
                        </button>
                    </div>
                    
                    <div id="ingredientes-container" class="space-y-3">
                        <div class="text-center text-muted py-4" id="no-ingredientes-msg">
                            <i class="fas fa-info-circle"></i> No hay ingredientes agregados
                        </div>
                    </div>
                    
                    <button type="button" onclick="agregarIngrediente()" class="mt-3 text-primary hover:text-secondary">
                        <i class="fas fa-plus-circle mr-1"></i> Agregar ingrediente existente
                    </button>
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

<!-- Modal para crear categoría -->
<div id="categoriaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Nueva Categoría</h3>
        <form id="categoriaForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre *</label>
                    <input type="text" name="nombre" required class="w-full px-4 py-2 rounded-lg border border-border">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Icono (Font Awesome)</label>
                    <input type="text" name="icono" placeholder="fa-utensils" class="w-full px-4 py-2 rounded-lg border border-border">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full px-4 py-2 rounded-lg border border-border"></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeCategoriaModal()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para crear ingrediente -->
<div id="ingredienteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Nuevo Ingrediente</h3>
        <form id="ingredienteForm" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre *</label>
                    <input type="text" name="nombre" required class="w-full px-4 py-2 rounded-lg border border-border">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Unidad de Medida *</label>
                    <select name="unidad_medida" required class="w-full px-4 py-2 rounded-lg border border-border">
                        <option value="gr">Gramos (gr)</option>
                        <option value="ml">Mililitros (ml)</option>
                        <option value="unidad">Unidad</option>
                        <option value="cda">Cucharada (cda)</option>
                        <option value="cdta">Cucharadita (cdta)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Foto</label>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeIngredienteModal()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Crear</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let ingredientesSeleccionados = [];

function agregarIngrediente() {
    // Mostrar modal o select de ingredientes existentes
    const ingredientesExistentes = @json($ingredientes);
    
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

function openCategoriaModal() {
    document.getElementById('categoriaModal').classList.remove('hidden');
    document.getElementById('categoriaModal').classList.add('flex');
}

function closeCategoriaModal() {
    document.getElementById('categoriaModal').classList.add('hidden');
    document.getElementById('categoriaModal').classList.remove('flex');
    document.getElementById('categoriaForm').reset();
}

function openIngredienteModal() {
    document.getElementById('ingredienteModal').classList.remove('hidden');
    document.getElementById('ingredienteModal').classList.add('flex');
}

function closeIngredienteModal() {
    document.getElementById('ingredienteModal').classList.add('hidden');
    document.getElementById('ingredienteModal').classList.remove('flex');
    document.getElementById('ingredienteForm').reset();
}

document.getElementById('categoriaForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('{{ route("categorias.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Agregar la nueva categoría al select
            const select = document.querySelector('select[name="categoria_id"]');
            const option = document.createElement('option');
            option.value = data.categoria.id;
            option.textContent = data.categoria.nombre;
            select.appendChild(option);
            select.value = data.categoria.id;
            
            closeCategoriaModal();
            alert('Categoría creada exitosamente');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear la categoría');
    }
});

document.getElementById('ingredienteForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('{{ route("ingredientes.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeIngredienteModal();
            // Recargar la página para mostrar el nuevo ingrediente en el select
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear el ingrediente');
    }
});
</script>
@endpush
@endsection