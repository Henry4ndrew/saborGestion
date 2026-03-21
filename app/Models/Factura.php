<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'total',
        'fecha',
        'estado'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'datetime'
    ];
}