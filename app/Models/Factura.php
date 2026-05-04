<?php
// app/Models/Factura.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

    protected $fillable = [
        'pedido_id',
        'numero_factura',
        'cliente_nombre',
        'cliente_nit',
        'cliente_telefono',
        'subtotal',
        'impuesto',
        'descuento',
        'total',
        'metodo_pago',
        'estado',
        'fecha_emision',
        'usuario_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_emision' => 'datetime'
    ];

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADA = 'pagada';
    const ESTADO_ANULADA = 'anulada';

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function generarNumeroFactura()
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        $numero = $ultimo ? intval(substr($ultimo->numero_factura, -6)) + 1 : 1;
        $this->numero_factura = 'FACT-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
        $this->save();
    }
}