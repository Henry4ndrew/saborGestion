<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'ingrediente',
        'cantidad',
        'unidad',
        'stock_minimo'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'stock_minimo' => 'decimal:2'
    ];
}