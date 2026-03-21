@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gastronomico-primary">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </a>
    </div>
    
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4">Nombre</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Rol</th>
                        <th class="text-left py-3 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $usuario->name }}</td>
                        <td class="py-3 px-4">{{ $usuario->email }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($usuario->role == 'admin') bg-red-100 text-red-800
                                @elseif($usuario->role == 'mesero') bg-blue-100 text-blue-800
                                @elseif($usuario->role == 'cocinero') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($usuario->role) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection