<?php
// app/Models/Comanda.php
namespace App\Models;

class Comanda extends Pedido
{
    protected $table = 'pedidos';
    // Scope para obtener solo pedidos activos para cocina
    public function scopeParaCocina($query)
    {
        return $query->whereIn('estado', [
            self::ESTADO_PENDIENTE,
            self::ESTADO_EN_PREPARACION,
            self::ESTADO_LISTO
        ])->orderBy('created_at', 'asc');
    }
    
    // Scope para pedidos pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }
    
    // Scope para pedidos en preparación
    public function scopeEnPreparacion($query)
    {
        return $query->where('estado', self::ESTADO_EN_PREPARACION);
    }
    
    // Scope para pedidos listos
    public function scopeListos($query)
    {
        return $query->where('estado', self::ESTADO_LISTO);
    }
}