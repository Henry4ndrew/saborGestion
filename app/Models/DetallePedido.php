<?php
// app/Models/DetallePedido.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
        'plato_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'notas',
        'estado'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Estados del detalle
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PREPARACION = 'en_preparacion';
    const ESTADO_LISTO = 'listo';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_CANCELADO = 'cancelado';

    // Relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }

    // Métodos
    public function calcularSubtotal()
    {
        $this->subtotal = $this->cantidad * $this->precio_unitario;
        $this->save();
    }

    public function actualizarEstado($estado)
    {
        $this->estado = $estado;
        $this->save();
        
        // Verificar si todos los detalles están entregados
        if ($estado == self::ESTADO_ENTREGADO && 
            $this->pedido->detalles->every(fn($d) => $d->estado == self::ESTADO_ENTREGADO)) {
            $this->pedido->actualizarEstado(Pedido::ESTADO_ENTREGADO);
        }
    }

    public static function getEstados()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_EN_PREPARACION => 'En Preparación',
            self::ESTADO_LISTO => 'Listo',
            self::ESTADO_ENTREGADO => 'Entregado',
            self::ESTADO_CANCELADO => 'Cancelado'
        ];
    }
}