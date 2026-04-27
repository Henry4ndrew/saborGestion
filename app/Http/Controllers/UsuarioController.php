<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();
            
            // Búsqueda por nombre o email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Filtro por rol
            if ($request->filled('rol')) {
                $query->where('role', $request->rol);
            }
            
            // Filtro por calificación
            if ($request->filled('calificacion')) {
                $calificacion = $request->calificacion;
                switch ($calificacion) {
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
            
            $perPage = $request->get('per_page', 15);
            $usuarios = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $usuarios,
                'total' => User::count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'string', Password::min(8)],
                'role' => 'required|in:admin,mesero,cocinero,cajero,cliente',
                'celular' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'score' => 'nullable|integer|min:0|max:5',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            if (!isset($validated['score'])) {
                $validated['score'] = 0;
            }

            $usuario = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $usuario
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $usuario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $usuario->id,
                'role' => 'sometimes|required|in:admin,mesero,cocinero,cajero,cliente',
                'celular' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'score' => 'nullable|integer|min:0|max:5',
                'password' => 'nullable|string|min:8',
            ]);

            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            }

            $usuario->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $usuario
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        try {
            // Evitar que el admin se elimine a sí mismo
            if (auth()->id() === $usuario->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propio usuario'
                ], 403);
            }

            $usuario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user score.
     */
    public function updateScore(Request $request, User $usuario)
    {
        try {
            $request->validate([
                'score' => 'required|integer|min:0|max:5'
            ]);

            $usuario->update(['score' => $request->score]);

            return response()->json([
                'success' => true,
                'message' => 'Calificación actualizada exitosamente',
                'data' => $usuario
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar calificación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics.
     */
    public function estadisticas()
{
    // Versión extremadamente simple para diagnóstico
    return response()->json([
        'success' => true,
        'message' => 'Método estadisticas funcionando',
        'total_usuarios' => User::count(),
        'timestamp' => now()->toDateTimeString()
    ]);
}
}