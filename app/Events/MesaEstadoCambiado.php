<?php

namespace App\Events;

use App\Models\Mesa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MesaEstadoCambiado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Mesa $mesa;
    public string $estado_anterior;

    public function __construct(Mesa $mesa, string $estado_anterior = '')
    {
        $this->mesa = $mesa;
        $this->estado_anterior = $estado_anterior;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('pedidos.meseros'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mesa.estado.cambiado';
    }

    public function broadcastWith(): array
    {
        return [
            'mesa_id' => $this->mesa->id,
            'numero_mesa' => $this->mesa->numero_mesa,
            'estado' => $this->mesa->estado,
            'estado_anterior' => $this->estado_anterior,
            'area' => $this->mesa->area,
            'capacidad' => $this->mesa->capacidad,
        ];
    }
}
