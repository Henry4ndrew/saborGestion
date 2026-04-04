<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Búsqueda inteligente por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Método 2: Usando LIKE normal (sin acentos, requiere normalización en PHP)
            $searchNormalized = $this->normalizeString($search);
            
            // Obtenemos todos los usuarios y filtramos en PHP (para evitar problemas SQL complejos)
            $allUsers = User::all();
            $filteredUsers = $allUsers->filter(function($user) use ($searchNormalized) {
                $nameNormalized = $this->normalizeString($user->name);
                $emailNormalized = $this->normalizeString($user->email);
                return str_contains($nameNormalized, $searchNormalized) || 
                       str_contains($emailNormalized, $searchNormalized);
            });
            
            $query->whereIn('id', $filteredUsers->pluck('id'));
        }
        
        // Filtro por rol
        if ($request->filled('rol') && $request->rol != '') {
            $query->where('role', $request->rol);
        }
        
        // Filtro por calificación (score de 0-5)
        if ($request->filled('calificacion') && $request->calificacion != '') {
            switch ($request->calificacion) {
                case '5':
                    $query->where('score', 5);
                    break;
                case '4':
                    $query->where('score', 4);
                    break;
                case '3':
                    $query->where('score', 3);
                    break;
                case '2':
                    $query->where('score', 2);
                    break;
                case '1':
                    $query->where('score', 1);
                    break;
                case '0':
                    $query->where('score', 0);
                    break;
                case 'alta':
                    $query->where('score', '>=', 4);
                    break;
                case 'media':
                    $query->whereBetween('score', [2, 3]);
                    break;
                case 'baja':
                    $query->where('score', '<=', 1);
                    break;
            }
        }
        
        $usuarios = $query->get();
        $totalUsuarios = User::count();
        $filtros = $request->only(['search', 'rol', 'calificacion']);
        
        return view('usuarios.index', compact('usuarios', 'totalUsuarios', 'filtros'));
    }
     private function normalizeString($string)
    {
        // Eliminar espacios
        $string = str_replace(' ', '', $string);
        
        // Convertir a minúsculas
        $string = mb_strtolower($string);
        
        // Eliminar acentos
        $acentos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n', 'ü' => 'u', 'Ü' => 'u'
        ];
        
        $string = strtr($string, $acentos);
        
        return $string;
    }




    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,mesero,cocinero,cajero,cliente',
            'celular' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'score' => 'nullable|integer|min:0|max:5',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if (!isset($validated['score'])) {
            $validated['score'] = 0;
        }

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required|in:admin,mesero,cocinero,cajero,cliente',
            'celular' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'score' => 'nullable|integer|min:0|max:5',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}