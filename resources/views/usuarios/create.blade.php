@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gastronomico-primary">Nuevo Usuario</h1>
        <a href="{{ route('usuarios.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
    
    <div class="card">
        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="form-label">Nombre *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="form-input @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           class="form-input @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="form-label">Contraseña *</label>
                    <input type="password" name="password" id="password" 
                           class="form-input @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rol -->
                <div>
                    <label for="role" class="form-label">Rol *</label>
                    <select name="role" id="role" class="form-input @error('role') border-red-500 @enderror" required>
                        <option value="">Seleccionar rol</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="mesero" {{ old('role') == 'mesero' ? 'selected' : '' }}>Mesero</option>
                        <option value="cocinero" {{ old('role') == 'cocinero' ? 'selected' : '' }}>Cocinero</option>
                        <option value="cajero" {{ old('role') == 'cajero' ? 'selected' : '' }}>Cajero</option>
                        <option value="cliente" {{ old('role') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Celular -->
                <div>
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" name="celular" id="celular" value="{{ old('celular') }}" 
                           class="form-input @error('celular') border-red-500 @enderror">
                    @error('celular')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" 
                           class="form-input @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="reset" class="btn-secondary">Limpiar</button>
                <button type="submit" class="btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection