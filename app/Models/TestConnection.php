<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestConnection extends Model
{
    protected $table = 'test_connection';

    protected $fillable = [
        'mensaje',
        'ssl_activo',
    ];

    protected $casts = [
        'ssl_activo' => 'boolean',
    ];
}
