{{-- resources/views/ingredientes/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Crear Nuevo Ingrediente</h1>
        <a href="{{ route('ingredientes.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="card">
        <form action="{{ route('ingredientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           required
                           class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">Unidad de Medida *</label>
                    <select name="unidad_medida" 
                            required
                            class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary @error('unidad_medida') border-red-500 @enderror">
                        <option value="gr" {{ old('unidad_medida') == 'gr' ? 'selected' : '' }}>Gramos (gr)</option>
                        <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                        <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="cda" {{ old('unidad_medida') == 'cda' ? 'selected' : '' }}>Cucharada (cda)</option>
                    </select>
                    @error('unidad_medida')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">Foto del Ingrediente</label>
                    <div class="mt-1 flex items-center space-x-4">
                        <div id="photoPreview" class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-border">
                            <i class="fas fa-camera text-gray-400 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   name="foto" 
                                   id="foto"
                                   accept="image/*"
                                   class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary">
                            <p class="text-xs text-muted mt-1">
                                <i class="fas fa-info-circle"></i> 
                                Formatos permitidos: JPG, PNG, GIF. Máximo 2MB
                            </p>
                        </div>
                    </div>
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-background rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-text mb-2">Información adicional</h3>
                    <p class="text-sm text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Los ingredientes pueden ser utilizados en múltiples platos. La cantidad se especificará al momento de crear o editar cada plato.
                    </p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('ingredientes.index') }}" class="btn-secondary px-6">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary px-6">
                        <i class="fas fa-save mr-2"></i> Guardar Ingrediente
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Vista previa de la foto
    const fotoInput = document.getElementById('foto');
    const photoPreview = document.getElementById('photoPreview');
    
    fotoInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
@endsection