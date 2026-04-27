<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'categoria_id',
        'descripcion',
        'disponible',
        'imagen',
        'score'
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'disponible' => 'boolean',
        'score' => 'decimal:1'
    ];
    
    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    
    // Relación con ingredientes (pivote con cantidad)
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'plato_ingrediente')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}