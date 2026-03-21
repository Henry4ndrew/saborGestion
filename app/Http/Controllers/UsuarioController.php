<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
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
            'role' => 'required|in:admin,mesero,cocinero,cajero',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
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
            'role' => 'required|in:admin,mesero,cocinero,cajero',
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }
        
        $usuario->update($validated);
        
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }
    
    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}