<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda #{{ $comanda->numero_pedido }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            background: white;
        }
        
        .comanda {
            max-width: 300px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
        }
        
        .items-table th,
        .items-table td {
            text-align: left;
            padding: 4px 0;
        }
        
        .items-table th {
            border-bottom: 1px dashed #000;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .mb-1 {
            margin-bottom: 5px;
        }
        
        .mt-2 {
            margin-top: 10px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        .btn-print {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #C2410C;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .estado-pendiente { background: #FEF3C7; color: #92400E; }
        .estado-preparacion { background: #DBEAFE; color: #1E40AF; }
        .estado-listo { background: #D1FAE5; color: #065F46; }
    </style>
</head>
<body>
    <div class="comanda">
        <div class="header">
            <h1>🍽️ COMANDA DE COCINA</h1>
            <p>{{ config('app.name', 'Restaurante') }}</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
        
        <div class="info">
            <div class="info-row">
                <span><strong>Pedido:</strong></span>
                <span>#{{ $comanda->numero_pedido }}</span>
            </div>
            <div class="info-row">
                <span><strong>Tipo:</strong></span>
                <span>{{ $tipos[$comanda->tipo_pedido] }}</span>
            </div>
            @if($comanda->tipo_pedido == 'mesa')
            <div class="info-row">
                <span><strong>Mesa:</strong></span>
                <span>Mesa {{ $comanda->mesa->numero_mesa ?? 'N/A' }}</span>
            </div>
            @else
            <div class="info-row">
                <span><strong>Cliente:</strong></span>
                <span>{{ $comanda->cliente_nombre }}</span>
            </div>
            <div class="info-row">
                <span><strong>Teléfono:</strong></span>
                <span>{{ $comanda->cliente_telefono }}</span>
            </div>
            @if($comanda->direccion)
            <div class="info-row">
                <span><strong>Dirección:</strong></span>
                <span>{{ $comanda->direccion }}</span>
            </div>
            @endif
            @endif
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Plato</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comanda->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>
                        {{ $detalle->plato->nombre }}
                        @if($detalle->notas)
                            <div style="font-size: 9px; color: #666;">* {{ $detalle->notas }}</div>
                        @endif
                    </td>
                    <td>
                        @if($detalle->estado == 'pendiente')
                            <span class="estado-badge estado-pendiente">⏳ Pend.</span>
                        @elseif($detalle->estado == 'en_preparacion')
                            <span class="estado-badge estado-preparacion">🔥 Prep.</span>
                        @elseif($detalle->estado == 'listo')
                            <span class="estado-badge estado-listo">✅ Listo</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($comanda->notas)
        <div class="footer">
            <strong>📝 Notas:</strong>
            <p>{{ $comanda->notas }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p><strong>Estado: 
                @if($comanda->estado == 'pendiente')
                    ⏳ Pendiente
                @elseif($comanda->estado == 'en_preparacion')
                    🔥 En Preparación
                @else
                    ✅ Listo para servir
                @endif
            </strong></p>
            <p class="mt-2">*** Comanda generada por {{ $comanda->usuario->name ?? 'Sistema' }} ***</p>
        </div>
    </div>
    
    <button onclick="window.print()" class="btn-print no-print">🖨️ Imprimir Comanda</button>
    
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>