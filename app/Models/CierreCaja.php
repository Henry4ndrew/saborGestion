<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierre_cajas';

    protected $fillable = [
        'fecha',
        'total_ventas',
        'total_efectivo',
        'total_tarjeta',
        'total_otros',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'total_ventas' => 'decimal:2',
        'total_efectivo' => 'decimal:2',
        'total_tarjeta' => 'decimal:2',
        'total_otros' => 'decimal:2'
    ];
}