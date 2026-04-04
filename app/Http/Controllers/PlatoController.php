<?php
// app/Http/Controllers/PlatoController.php

namespace App\Http\Controllers;

use App\Models\Plato;
use App\Models\Categoria;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatoController extends Controller
{
    public function index(Request $request)
    {
        $query = Plato::with('categoria');
        
        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }
        
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        
        // Filtro por disponibilidad
        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible === 'true');
        }
        
        // Filtro por score
        if ($request->filled('score')) {
            $score = $request->score;
            if ($score === 'alta') {
                $query->where('score', '>=', 4);
            } elseif ($score === 'media') {
                $query->whereBetween('score', [2, 3.9]);
            } elseif ($score === 'baja') {
                $query->where('score', '<', 2);
            } else {
                $query->where('score', '>=', $score);
            }
        }
        
        $platos = $query->orderBy('nombre')->paginate(15);
        $categorias = Categoria::where('activo', true)->get();
        $totalPlatos = Plato::count();
        
        return view('platos.index', compact('platos', 'categorias', 'totalPlatos'));
    }
    
    public function create()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        
        return view('platos.create', compact('categorias', 'ingredientes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'disponible' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredientes' => 'array',
            'ingredientes.*.id' => 'exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required|numeric|min:0.01',
        ]);
        
        $plato = new Plato();
        $plato->nombre = $request->nombre;
        $plato->precio = $request->precio;
        $plato->categoria_id = $request->categoria_id;
        $plato->descripcion = $request->descripcion;
        $plato->disponible = $request->has('disponible');
        $plato->score = 0; // Inicialmente en 0
        
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('platos', 'public');
            $plato->imagen = $path;
        }
        
        $plato->save();
        
        // Guardar ingredientes
        if ($request->has('ingredientes')) {
            foreach ($request->ingredientes as $ingrediente) {
                $plato->ingredientes()->attach($ingrediente['id'], [
                    'cantidad' => $ingrediente['cantidad']
                ]);
            }
        }
        
        return redirect()->route('platos.index')
            ->with('success', 'Plato creado exitosamente');
    }
    
    public function edit(Plato $plato)
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        $plato->load('ingredientes');
        
        return view('platos.edit', compact('plato', 'categorias', 'ingredientes'));
    }
    
    public function update(Request $request, Plato $plato)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'disponible' => 'boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredientes' => 'array',
            'ingredientes.*.id' => 'exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required|numeric|min:0.01',
        ]);
        
        $plato->nombre = $request->nombre;
        $plato->precio = $request->precio;
        $plato->categoria_id = $request->categoria_id;
        $plato->descripcion = $request->descripcion;
        $plato->disponible = $request->has('disponible');
        
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($plato->imagen) {
                Storage::disk('public')->delete($plato->imagen);
            }
            $path = $request->file('imagen')->store('platos', 'public');
            $plato->imagen = $path;
        }
        
        $plato->save();
        
        // Actualizar ingredientes
        $ingredientesData = [];
        if ($request->has('ingredientes')) {
            foreach ($request->ingredientes as $ingrediente) {
                $ingredientesData[$ingrediente['id']] = [
                    'cantidad' => $ingrediente['cantidad']
                ];
            }
        }
        $plato->ingredientes()->sync($ingredientesData);
        
        return redirect()->route('platos.index')
            ->with('success', 'Plato actualizado exitosamente');
    }
    
    public function destroy(Plato $plato)
    {
        // Eliminar imagen asociada
        if ($plato->imagen) {
            Storage::disk('public')->delete($plato->imagen);
        }
        
        $plato->delete();
        
        return redirect()->route('platos.index')
            ->with('success', 'Plato eliminado exitosamente');
    }
    
    public function toggleDisponible(Plato $plato)
    {
        $plato->disponible = !$plato->disponible;
        $plato->save();
        
        return response()->json([
            'success' => true,
            'disponible' => $plato->disponible
        ]);
    }
}