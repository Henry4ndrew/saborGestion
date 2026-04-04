<?php
// app/Models/Plato.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plato extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'categoria_id',
        'imagen',
        'disponible',
        'score',
        'descripcion'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'precio' => 'decimal:2',
        'score' => 'decimal:1',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediente::class, 'plato_ingrediente')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    // Actualizar el score promedio basado en calificaciones de usuarios
    public function actualizarScore(): void
    {
        // Aquí puedes implementar la lógica para calcular el score
        // basado en calificaciones de usuarios que han pedido este plato
        $this->save();
    }
}