<?php
// app/Models/Ingrediente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingrediente extends Model
{
    protected $fillable = [
        'nombre',
        'foto',
        'unidad_medida'
    ];

    public function platos(): BelongsToMany
    {
        return $this->belongsToMany(Plato::class, 'plato_ingrediente')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}