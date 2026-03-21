<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'monto',
        'metodo',
        'fecha'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'datetime'
    ];
}