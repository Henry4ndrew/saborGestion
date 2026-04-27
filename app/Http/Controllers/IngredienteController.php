<?php
// app/Http/Controllers/IngredienteController.php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IngredienteController extends Controller
{
    /**
     * Mostrar lista de ingredientes
     */
    public function index(Request $request)
    {
        $query = Ingrediente::query();
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%");
        }
        
        // Filtro por unidad de medida
        if ($request->filled('unidad')) {
            $query->where('unidad_medida', $request->unidad);
        }
        
        $ingredientes = $query->withCount('platos')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();
        
        $totalIngredientes = Ingrediente::count();
        $unidadesMedida = ['gr', 'ml', 'unidad', 'cda', 'cdta'];
        
        return view('ingredientes.index', compact('ingredientes', 'totalIngredientes', 'unidadesMedida'));
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $unidadesMedida = ['gr', 'ml', 'unidad', 'cda', 'cdta'];
        return view('ingredientes.create', compact('unidadesMedida'));
    }
    
    /**
     * Guardar nuevo ingrediente
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:ingredientes,nombre',
            'unidad_medida' => 'required|string|in:gr,ml,unidad,cda,cdta',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $ingrediente = new Ingrediente();
        $ingrediente->nombre = $request->nombre;
        $ingrediente->unidad_medida = $request->unidad_medida;
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('ingredientes', 'public');
            $ingrediente->foto = $path;
        }
        
        $ingrediente->save();
        
        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente creado exitosamente');
    }
    
    /**
     * Mostrar detalle de ingrediente
     */
    public function show(Ingrediente $ingrediente)
    {
        $ingrediente->load('platos');
        return view('ingredientes.show', compact('ingrediente'));
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(Ingrediente $ingrediente)
    {
        $unidadesMedida = ['gr', 'ml', 'unidad', 'cda', 'cdta'];
        return view('ingredientes.edit', compact('ingrediente', 'unidadesMedida'));
    }
    
    /**
     * Actualizar ingrediente
     */
    public function update(Request $request, Ingrediente $ingrediente)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:ingredientes,nombre,' . $ingrediente->id,
            'unidad_medida' => 'required|string|in:gr,ml,unidad,cda,cdta',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $ingrediente->nombre = $request->nombre;
        $ingrediente->unidad_medida = $request->unidad_medida;
        
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($ingrediente->foto) {
                Storage::disk('public')->delete($ingrediente->foto);
            }
            $path = $request->file('foto')->store('ingredientes', 'public');
            $ingrediente->foto = $path;
        }
        
        $ingrediente->save();
        
        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente actualizado exitosamente');
    }
    
    /**
     * Eliminar ingrediente
     */
    public function destroy(Ingrediente $ingrediente)
    {
        // Verificar si está siendo usado en algún plato
        if ($ingrediente->platos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el ingrediente porque está siendo usado en platos');
        }
        
        // Eliminar foto si existe
        if ($ingrediente->foto) {
            Storage::disk('public')->delete($ingrediente->foto);
        }
        
        $ingrediente->delete();
        
        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente eliminado exitosamente');
    }
    
    /**
     * Obtener lista de ingredientes para select (API)
     * Este método se mantiene para uso en formularios de platos
     */
    public function getList()
    {
        $ingredientes = Ingrediente::orderBy('nombre')->get(['id', 'nombre', 'unidad_medida']);
        return response()->json($ingredientes);
    }
}