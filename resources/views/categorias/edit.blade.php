{{-- resources/views/categorias/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Editar Categoría: {{ $categoria->nombre }}</h1>
        <a href="{{ route('categorias.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="card">
        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Nombre *</label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre', $categoria->nombre) }}" 
                           required
                           class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>



                <div>
                    <label class="block text-sm font-medium text-text mb-2">Icono (Font Awesome)</label>
                    {{-- Componente reutilizable --}}
                    <x-icon-picker :selectedIcon="old('icono', $categoria->icono ?? 'fa-tag')" name="icono" />
                    @error('icono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>



                
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Descripción</label>
                    <textarea name="descripcion" 
                              rows="4" 
                              class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="activo" 
                               value="1" 
                               {{ old('activo', $categoria->activo) ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-sm text-text">Categoría activa</span>
                    </label>
                </div>

                <!-- Información adicional -->
                <div class="bg-background rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-text mb-2">Información estadística</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-muted">Fecha de creación:</span>
                            <p class="font-medium">{{ $categoria->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Última actualización:</span>
                            <p class="font-medium">{{ $categoria->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-muted">Total de platos:</span>
                            <p class="font-medium text-primary">{{ $categoria->platos_count ?? $categoria->platos()->count() }} platos</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('categorias.index') }}" class="btn-secondary px-6">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary px-6">
                        <i class="fas fa-save mr-2"></i> Actualizar Categoría
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection