@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gastronomico-primary">Editar Usuario</h1>
        <a href="{{ route('usuarios.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
    
    <div class="card">
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="form-label">Nombre *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" 
                           class="form-input @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" 
                           class="form-input @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña (opcional) -->
                <div>
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" 
                           class="form-input @error('password') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Dejar en blanco para mantener la contraseña actual</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rol -->
                <div>
                    <label for="role" class="form-label">Rol *</label>
                    <select name="role" id="role" class="form-input @error('role') border-red-500 @enderror" required>
                        <option value="">Seleccionar rol</option>
                        <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="mesero" {{ old('role', $usuario->role) == 'mesero' ? 'selected' : '' }}>Mesero</option>
                        <option value="cocinero" {{ old('role', $usuario->role) == 'cocinero' ? 'selected' : '' }}>Cocinero</option>
                        <option value="cajero" {{ old('role', $usuario->role) == 'cajero' ? 'selected' : '' }}>Cajero</option>
                        <option value="cliente" {{ old('role', $usuario->role) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Celular -->
                <div>
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" name="celular" id="celular" value="{{ old('celular', $usuario->celular) }}" 
                           class="form-input @error('celular') border-red-500 @enderror">
                    @error('celular')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $usuario->direccion) }}" 
                           class="form-input @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('usuarios.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection