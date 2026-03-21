<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'mesa_id',
        'cliente',
        'total',
        'estado',
        'fecha'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'datetime'
    ];
}