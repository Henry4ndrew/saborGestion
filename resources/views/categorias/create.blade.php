{{-- resources/views/categorias/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Crear Nueva Categoría</h1>
        <a href="{{ route('categorias.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="card">
        <form action="{{ route('categorias.store') }}" method="POST">
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
                    <label class="block text-sm font-medium text-text mb-2">Icono (Font Awesome)</label>
                    {{-- Componente reutilizable --}}
                    <x-icon-picker :selectedIcon="old('icono', 'fa-tag')" name="icono" />
                    @error('icono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                

                
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Descripción</label>
                    <textarea name="descripcion" 
                              rows="4" 
                              class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:border-primary @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="activo" 
                               value="1" 
                               {{ old('activo', true) ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-sm text-text">Categoría activa</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('categorias.index') }}" class="btn-secondary px-6">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary px-6">
                        <i class="fas fa-save mr-2"></i> Guardar Categoría
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection