<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoEstadoActualizado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;
    public $numero_pedido;
    public $mesa_numero;
    public $mesa_zona;
    public $usuario_responsable_id;
    public $tiempo_finalizacion;
    public $estado_anterior;

    /**
     * Create a new event instance.
     */
    public function __construct(Pedido $pedido, $estado_anterior = null)
    {
        $this->pedido = $pedido;
        $this->numero_pedido = $pedido->numero_pedido;
        $this->mesa_numero = $pedido->mesa?->numero ?? 'N/A';
        $this->mesa_zona = $pedido->mesa?->zona ?? 'N/A';
        $this->usuario_responsable_id = $pedido->usuario_id;
        $this->tiempo_finalizacion = $pedido->updated_at;
        $this->estado_anterior = $estado_anterior;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('pedidos.cocineros'),
            new Channel('pedidos.meseros'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'pedido.estado_actualizado';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pedido->id,
            'numero_pedido' => $this->numero_pedido,
            'estado' => $this->pedido->estado,
            'estado_anterior' => $this->estado_anterior,
            'mesa_numero' => $this->mesa_numero,
            'mesa_zona' => $this->mesa_zona,
            'usuario_id' => $this->usuario_responsable_id,
            'tiempo_finalizacion' => $this->tiempo_finalizacion->format('H:i')
        ];
    }
}
