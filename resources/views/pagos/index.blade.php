@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary">Reporte Pagos</h1>
            <!-- <p class="text-sm text-gray-500">Cobros recibidos vía QR, efectivo y tarjeta. Las confirmaciones de QR llegan en vivo desde el webhook.</p> -->
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-4 text-white rounded-lg shadow-lg bg-gradient-to-br from-emerald-600 to-emerald-500">
            <p class="text-sm opacity-90">Total cobrado</p>
            <p class="text-2xl font-bold">Bs {{ number_format($kpis['total_pagado'], 2) }}</p>
            <p class="mt-1 text-xs opacity-80">{{ $kpis['count_pagadas'] }} facturas pagadas</p>
        </div>
        <div class="p-4 text-white rounded-lg shadow-lg bg-gradient-to-br from-amber-600 to-amber-500">
            <p class="text-sm opacity-90">Pendiente de cobro</p>
            <p class="text-2xl font-bold">Bs {{ number_format($kpis['total_pendiente'], 2) }}</p>
            <p class="mt-1 text-xs opacity-80">{{ $kpis['count_pendientes'] }} facturas pendientes</p>
        </div>
        <div class="p-4 text-white rounded-lg shadow-lg bg-gradient-to-br from-indigo-600 to-indigo-500">
            <p class="text-sm opacity-90">Cobrado vía QR</p>
            <p class="text-2xl font-bold">Bs {{ number_format($kpis['pagado_qr'], 2) }}</p>
            <p class="mt-1 text-xs opacity-80"><i class="mr-1 fas fa-qrcode"></i>Pagos digitales</p>
        </div>
        <div class="p-4 text-white rounded-lg shadow-lg bg-gradient-to-br from-slate-600 to-slate-500">
            <p class="text-sm opacity-90">Otros métodos</p>
            <p class="text-2xl font-bold">Bs {{ number_format($kpis['pagado_efectivo'] + $kpis['pagado_tarjeta'], 2) }}</p>
            <p class="mt-1 text-xs opacity-80">Efectivo + Tarjeta</p>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('pagos.index') }}" class="grid grid-cols-1 gap-3 p-4 bg-white rounded-lg shadow sm:grid-cols-2 lg:grid-cols-5">
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="w-full text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="w-full text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Estado</label>
            <select name="estado" class="w-full text-sm border-gray-300 rounded-md">
                <option value="todos"     {{ $estado=='todos'     ? 'selected' : '' }}>Todos</option>
                <option value="pendiente" {{ $estado=='pendiente' ? 'selected' : '' }}>Pendientes</option>
                <option value="pagada"    {{ $estado=='pagada'    ? 'selected' : '' }}>Pagadas</option>
                <option value="anulada"   {{ $estado=='anulada'   ? 'selected' : '' }}>Anuladas</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-xs font-medium text-gray-700">Método</label>
            <select name="metodo" class="w-full text-sm border-gray-300 rounded-md">
                <option value="todos"    {{ $metodo=='todos'    ? 'selected' : '' }}>Todos</option>
                <option value="qr"       {{ $metodo=='qr'       ? 'selected' : '' }}>QR</option>
                <option value="efectivo" {{ $metodo=='efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta"  {{ $metodo=='tarjeta'  ? 'selected' : '' }}>Tarjeta</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-primary hover:bg-primary/90">
                <i class="mr-1 fas fa-filter"></i> Filtrar
            </button>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">Factura</th>
                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">Pedido</th>
                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">Cliente</th>
                        <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase">Método</th>
                        <th class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase">Estado</th>
                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">Emitida</th>
                        <th class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($facturas as $f)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $f->numero_factura }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ optional($f->pedido)->numero_pedido ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $f->cliente_nombre ?? 'Cliente' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900">Bs {{ number_format($f->total, 2) }}</td>
                            <td class="px-4 py-3 text-xs text-center">
                                @if($f->metodo_pago === 'qr')
                                    <span class="px-2 py-1 text-indigo-800 bg-indigo-100 rounded-full"><i class="mr-1 fas fa-qrcode"></i>QR</span>
                                @elseif($f->metodo_pago === 'efectivo')
                                    <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800"><i class="mr-1 fas fa-money-bill"></i>Efectivo</span>
                                @elseif($f->metodo_pago === 'tarjeta')
                                    <span class="px-2 py-1 text-blue-800 bg-blue-100 rounded-full"><i class="mr-1 far fa-credit-card"></i>Tarjeta</span>
                                @else
                                    <span class="px-2 py-1 text-gray-700 bg-gray-100 rounded-full">{{ $f->metodo_pago ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                @if($f->estado === 'pagada')
                                    <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">Pagada</span>
                                @elseif($f->estado === 'pendiente')
                                    <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800">Pendiente</span>
                                @else
                                    <span class="px-2 py-1 text-red-800 bg-red-100 rounded-full">Anulada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ optional($f->fecha_emision)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 space-x-2 text-sm text-center whitespace-nowrap">
                                <a href="{{ route('facturas.show', $f) }}" class="text-blue-600 hover:underline" title="Ver factura"><i class="fas fa-eye"></i></a>
                                <!-- @if($f->estado === 'pendiente')
                                    <button type="button"
                                            onclick="mostrarQR('{{ route('facturas.generar-qr', $f) }}')"
                                            class="text-indigo-600 hover:underline"
                                            title="Generar QR de cobro">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                @endif -->
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-sm text-center text-gray-500">Sin facturas en el rango seleccionado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $facturas->links() }}</div>
    </div>
</div>

{{-- Modal QR --}}
<div id="qrModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black/60" onclick="cerrarQR(event)">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">QR de cobro</h3>
                <p id="qrFactura" class="text-sm text-gray-500"></p>
            </div>
            <button onclick="cerrarQR()" class="text-2xl leading-none text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <div id="qrLoader" class="py-8 text-center">
            <i class="text-3xl text-indigo-500 fas fa-spinner fa-spin"></i>
            <p class="mt-2 text-sm text-gray-500">Generando QR...</p>
        </div>

        <div id="qrContent" class="hidden">
            <div id="qrSvgWrap" class="flex justify-center p-4 bg-white border rounded-lg"></div>
            <div class="mt-4 space-y-1 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Cliente:</span><span id="qrCliente" class="font-medium"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Total a cobrar:</span><span id="qrTotal" class="font-bold text-emerald-600"></span></div>
            </div>
            <p class="mt-3 text-xs text-center text-gray-500">
                <i class="mr-1 fas fa-circle-notch fa-spin"></i>
                Esperando confirmación del pago...
            </p>

            {{-- Botón "Marcar como pagado" — confirma manualmente sin esperar
                 el webhook externo. Útil cuando el sistema externo del QR no
                 puede llegar a esta URL, o para demos. --}}
            <div class="pt-3 mt-3 border-t border-dashed">
                <button type="button"
                        onclick="simularPago()"
                        class="w-full py-2 text-xs font-medium text-white rounded-md bg-emerald-600 hover:bg-emerald-700">
                    <i class="mr-1 fas fa-check-circle"></i> Confirmar pago manualmente
                </button>
                <p class="text-[10px] text-gray-400 text-center mt-1">
                    Usá este botón si el sistema externo no confirma automáticamente.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let canalActivo = null;       // canal Echo al que estamos suscritos para el QR abierto
let pagoActivo  = null;       // datos del último QR generado (para "simular pago")

function mostrarQR(url) {
    const modal      = document.getElementById('qrModal');
    const loader     = document.getElementById('qrLoader');
    const content    = document.getElementById('qrContent');
    const svgWrap    = document.getElementById('qrSvgWrap');
    const facturaTxt = document.getElementById('qrFactura');
    const clienteTxt = document.getElementById('qrCliente');
    const totalTxt   = document.getElementById('qrTotal');

    modal.classList.remove('hidden');
    loader.classList.remove('hidden');
    content.classList.add('hidden');
    svgWrap.innerHTML = '';

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            svgWrap.innerHTML = data.qr_svg;
            facturaTxt.textContent = data.factura.numero_factura;
            clienteTxt.textContent = data.factura.cliente_nombre || 'Cliente';
            totalTxt.textContent   = 'Bs ' + data.factura.total;
            loader.classList.add('hidden');
            content.classList.remove('hidden');

            pagoActivo = {
                emisor: data.emisor,
                facturaId: data.factura.id,
                pedidoId: data.factura.pedido_id,
                total: data.factura.total,
            };

            // Suscribirse al canal específico de este pago.
            // PagoConfirmadoEvent::broadcastOn() => 'emisor-' . $emisor
            // PagoConfirmadoEvent::broadcastAs() => 'pago.confirmado'
            // En Echo, los eventos custom se prefijan con '.'.
            suscribirAlPago(data.emisor, data.factura.id);
        })
        .catch(err => {
            loader.innerHTML = '<p class="text-sm text-red-600">Error generando QR: ' + err.message + '</p>';
        });
}

function suscribirAlPago(emisor, facturaId) {
    if (typeof window.Echo === 'undefined') {
        console.warn('Echo no disponible. Reverb no está corriendo o no se compiló bootstrap.js.');
        return;
    }

    // Limpiar suscripción anterior si existe.
    if (canalActivo) {
        try { window.Echo.leave(canalActivo); } catch (e) {}
    }

    const nombreCanal = 'emisor-' + emisor;
    canalActivo = nombreCanal;
    console.log('🔔 Suscrito al canal:', nombreCanal, '(esperando evento .pago.confirmado)');

    window.Echo.channel(nombreCanal).listen('.pago.confirmado', (e) => {
        console.log('💸 Pago confirmado:', e);
        const content = document.getElementById('qrContent');
        content.innerHTML = `
            <div class="py-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-emerald-100">
                    <i class="text-3xl fas fa-check text-emerald-600"></i>
                </div>
                <h4 class="text-xl font-bold text-emerald-700">¡Pago confirmado!</h4>
                <p class="mt-2 text-sm text-gray-600">Bs ${(e.monto ?? 0)} cobrado vía QR</p>
                <p class="mt-2 text-xs text-gray-400">Refrescando...</p>
            </div>
        `;
        setTimeout(() => location.reload(), 1500);
    });
}

function cerrarQR(ev) {
    if (ev && ev.target.id !== 'qrModal') return;
    document.getElementById('qrModal').classList.add('hidden');
    if (canalActivo) {
        try { window.Echo.leave(canalActivo); } catch (e) {}
        canalActivo = null;
    }
    pagoActivo = null;
}

// Simula la llamada que el sitio externo del QR debería hacer al webhook.
// Solo aparece en local (el botón está condicionado a app()->environment('local')).
function simularPago() {
    if (!pagoActivo) return;
    fetch('{{ url('/api/confirmar-pago-qr') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
        body: JSON.stringify({
            emisor: pagoActivo.emisor,
            pedido: pagoActivo.pedidoId,
            monto: pagoActivo.total,
        }),
    })
    .then(r => r.json())
    .then(data => {
        console.log('Simulación pago →', data);
        // El front se va a refrescar solo cuando llegue el evento Pusher.
    })
    .catch(err => {
        alert('Error simulando pago: ' + err.message);
    });
}

// Cerrar con ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarQR();
});
</script>
@endpush
@endsection
