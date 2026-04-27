<?php
// app/Http/Controllers/CategoriaController.php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Mostrar lista de categorías
     */
    public function index()
    {
        $categorias = Categoria::withCount('platos')
            ->orderBy('nombre')
            ->get();
        
        return view('categorias.index', compact('categorias'));
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('categorias.create');
    }
    
    /**
     * Guardar nueva categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'icono' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
        ]);
        
        Categoria::create([
            'nombre' => $request->nombre,
            'icono' => $request->icono,
            'descripcion' => $request->descripcion,
            'activo' => true,
        ]);
        
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }
    
    /**
     * Mostrar detalle de categoría
     */
    public function show(Categoria $categoria)
    {
        $categoria->load('platos');
        return view('categorias.show', compact('categoria'));
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }
    
    /**
     * Actualizar categoría
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'icono' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
        ]);
        
        $categoria->update([
            'nombre' => $request->nombre,
            'icono' => $request->icono,
            'descripcion' => $request->descripcion,
        ]);
        
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }
    
    /**
     * Eliminar categoría
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->platos()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene platos asociados');
        }
        
        $categoria->delete();
        
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
    
    /**
     * Activar/Desactivar categoría
     */
    public function toggleActivo(Categoria $categoria)
    {
        $categoria->activo = !$categoria->activo;
        $categoria->save();
        
        $message = $categoria->activo ? 'Categoría activada' : 'Categoría desactivada';
        
        return redirect()->route('categorias.index')
            ->with('success', $message);
    }
}