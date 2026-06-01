@extends('layouts.app')

@section('content')
<div class="min-h-screen px-4 py-6 bg-gradient-to-br from-amber-50/30 via-orange-50/20 to-rose-50/30 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Encabezado -->
        <div class="relative mb-8 overflow-hidden bg-white shadow-xl rounded-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5"></div>
            <div class="absolute top-0 right-0 w-64 h-64 translate-x-32 -translate-y-32 rounded-full bg-gradient-to-br from-primary/10 to-transparent blur-3xl"></div>
            <div class="relative px-6 py-8 sm:px-8 sm:py-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="p-2 shadow-lg bg-gradient-to-br from-primary to-secondary rounded-xl">
                                <i class="text-2xl text-white fas fa-cash-register"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-transparent bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text sm:text-4xl">
                                    Dashboard Cajero
                                </h1>
                                <p class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                    <i class="text-xs fas fa-calculator text-amber-500"></i>
                                    Panel de control de caja y transacciones
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="flex items-center gap-3 px-4 py-2 border border-gray-100 shadow-sm bg-gray-50/80 backdrop-blur-sm rounded-2xl">
                            <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-xl">
                                <i class="text-xs text-white fas fa-calendar-alt"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium tracking-wider text-gray-500 uppercase">Fecha actual</p>
                                <p class="text-sm font-semibold text-gray-800">{{ now()->format('l, d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mt-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-primary bg-primary/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-chart-line"></i>
                        {{ $cajaAbierta ? 'Caja Abierta' : 'Caja Cerrada' }}
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-secondary bg-secondary/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-store"></i>
                        Turno: {{ now()->format('H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium rounded-full text-accent bg-accent/10 backdrop-blur-sm">
                        <i class="text-xs fas fa-clock"></i>
                        Última transacción: {{ $ultimosPagos->first() ? $ultimosPagos->first()->fecha_emision->format('H:i') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Ventas Totales -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-primary/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Ventas Totales</p>
                            <p class="text-4xl font-bold text-gray-800">${{ number_format($ventasHoy, 2) }}</p>
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-green-600 bg-green-100 rounded-full">
                                    <i class="mr-1 text-xs fas fa-arrow-up"></i>{{ $pedidosCobradosHoy > 0 ? round(($ventasHoy / max(1, $pedidosCobradosHoy)), 2) : 0 }}%
                                </span>
                                <span class="text-xs text-gray-400">vs ticket promedio</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-primary/10 to-primary/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-chart-line text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-primary to-secondary group-hover:scale-x-100"></div>
            </div>

            <!-- Pedidos Hoy -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-secondary/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Pedidos Hoy</p>
                            <p class="text-4xl font-bold text-gray-800">{{ $pedidosCobradosHoy }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Pendientes:</span>
                                <span class="text-xs font-semibold text-amber-600">{{ $pedidosPendientesPago }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-shopping-cart text-secondary"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-secondary to-accent group-hover:scale-x-100"></div>
            </div>

            <!-- Clientes Atendidos -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-accent/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Clientes Atendidos</p>
                            <p class="text-4xl font-bold text-gray-800">{{ $clientesAtendidos }}</p>
                            <div class="flex items-center gap-2">
                                <i class="text-xs text-accent fas fa-user-plus"></i>
                                <span class="text-xs text-gray-500">Hoy: {{ $clientesAtendidos }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-accent/10 to-accent/5 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-users text-accent"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-accent to-amber-500 group-hover:scale-x-100"></div>
            </div>

            <!-- Productos Agotados -->
            <div class="relative overflow-hidden transition-all duration-500 bg-white shadow-xl group rounded-2xl hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-red-500/5 to-transparent blur-2xl"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Productos Agotados</p>
                            <p class="text-4xl font-bold text-rose-600">{{ $productosAgotados }}</p>
                            <div class="flex items-center gap-1">
                                <i class="text-xs text-amber-500 fas fa-exclamation-triangle"></i>
                                <span class="text-xs text-gray-500">Requieren reposición</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center transition-transform duration-300 w-14 h-14 bg-gradient-to-br from-rose-100 to-amber-50 rounded-2xl group-hover:scale-110">
                            <i class="text-3xl fas fa-box-open text-rose-500"></i>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 transition-transform duration-500 origin-left scale-x-0 bg-gradient-to-r from-rose-500 to-amber-500 group-hover:scale-x-100"></div>
            </div>
        </div>

        <!-- Pedidos listos para cobrar -->
        <div class="mt-6">
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-6 rounded-full bg-gradient-to-b from-emerald-500 to-teal-500"></div>
                            <h2 class="text-lg font-bold text-gray-800">Pedidos listos para cobrar</h2>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full text-emerald-700 bg-emerald-100">
                            {{ $pedidosEntregados->count() }} pedido(s)
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($pedidosEntregados->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="flex items-center justify-center w-20 h-20 mb-4 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <i class="text-4xl text-gray-300 fas fa-inbox"></i>
                            </div>
                            <p class="font-semibold text-gray-600">Sin pedidos entregados aún</p>
                            <p class="mt-1 text-sm text-gray-400">Cuando el mesero finalice un pedido aparecerá aquí</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($pedidosEntregados as $pedido)
                                <div class="flex flex-col gap-4 p-4 transition-all border sm:flex-row sm:items-center sm:justify-between bg-emerald-50 border-emerald-200 rounded-xl hover:shadow-md">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center justify-center w-12 h-12 shadow bg-emerald-100 rounded-xl shrink-0">
                                            <i class="text-lg fas fa-receipt text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">Pedido #{{ $pedido->numero_pedido }}</p>
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                @if($pedido->mesa)
                                                    <span class="text-xs text-gray-500"><i class="mr-1 fas fa-chair"></i>Mesa {{ $pedido->mesa->numero }}</span>
                                                @endif
                                                @if($pedido->usuario)
                                                    <span class="text-xs text-gray-500"><i class="mr-1 fas fa-user"></i>{{ $pedido->usuario->name }}</span>
                                                @endif
                                                <span class="text-xs text-gray-400"><i class="mr-1 fas fa-clock"></i>{{ $pedido->updated_at->format('d/m H:i') }}</span>
                                                <span class="text-xs font-bold text-emerald-700">${{ number_format($pedido->total, 2) }}</span>
                                            </div>
                                            @if($pedido->detalles && $pedido->detalles->count())
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $pedido->detalles->count() }} ítem(s):
                                                    {{ $pedido->detalles->take(3)->map(fn($d) => $d->plato?->nombre ?? 'Ítem')->join(', ') }}
                                                    {{ $pedido->detalles->count() > 3 ? '…' : '' }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <a href="{{ route('facturas.index', ['pedido_id' => $pedido->id]) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 shadow bg-gradient-to-r from-primary to-secondary rounded-xl hover:shadow-lg hover:scale-105">
                                            <i class="fas fa-file-invoice-dollar"></i> Facturar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Secciones principales: Ventas Recientes y Productos Más Vendidos -->
        <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
            <!-- Ventas Recientes (Últimos pagos) -->
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-primary to-secondary"></div>
                                <h2 class="text-lg font-bold text-gray-800">Últimos Pagos</h2>
                            </div>
                            <p class="text-xs text-gray-500">Transacciones recientes registradas</p>
                        </div>
                        <a href="{{ route('facturas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 group bg-gray-50 text-primary rounded-xl hover:bg-primary hover:text-white hover:shadow-md">
                            <span>Ver todas</span>
                            <i class="text-xs transition-transform fas fa-arrow-right group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($ultimosPagos->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="relative">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-primary/10 to-secondary/10 blur-xl"></div>
                                <div class="relative flex items-center justify-center w-24 h-24 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                    <i class="text-4xl text-gray-400 fas fa-receipt"></i>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <p class="font-medium text-gray-700">No hay pagos registrados</p>
                                <p class="mt-1 text-sm text-gray-400">Los pagos aparecerán automáticamente aquí</p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-2 text-left">Factura</th>
                                        <th class="py-2 text-left">Cliente</th>
                                        <th class="py-2 text-left">Método</th>
                                        <th class="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ultimosPagos as $pago)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                                        <td class="py-2 font-medium text-gray-700">{{ $pago->numero_factura }}</td>
                                        <td class="py-2 text-gray-600">{{ $pago->cliente_nombre }}</td>
                                        <td class="py-2">
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full
                                                @if($pago->metodo_pago == 'efectivo') bg-green-100 text-green-700
                                                @elseif($pago->metodo_pago == 'tarjeta') bg-blue-100 text-blue-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pago->metodo_pago) }}
                                            </span>
                                        </td>
                                        <td class="py-2 font-semibold text-right text-gray-800">${{ number_format($pago->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-secondary/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-secondary to-accent"></div>
                                <h2 class="text-lg font-bold text-gray-800">Productos Más Vendidos</h2>
                            </div>
                            <p class="text-xs text-gray-500">Los productos con mayor demanda del día</p>
                        </div>
                        <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 group bg-gray-50 text-secondary rounded-xl hover:bg-secondary hover:text-white hover:shadow-md">
                            <span>Ver todos</span>
                            <i class="text-xs transition-transform fas fa-arrow-right group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    @if($productosMasVendidos->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="relative">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-secondary/10 to-accent/10 blur-xl"></div>
                                <div class="relative flex items-center justify-center w-24 h-24 shadow-inner bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                    <i class="text-4xl text-gray-400 fas fa-chart-simple"></i>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <p class="font-medium text-gray-700">No hay datos disponibles</p>
                                <p class="mt-1 text-sm text-gray-400">Las estadísticas se generarán automáticamente</p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($productosMasVendidos as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary/10">
                                        <i class="text-secondary fas fa-utensils"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $item->nombre }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-secondary">{{ $item->total_vendido }}</span>
                                    <span class="text-xs text-gray-400">unidades</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen de Caja -->
        <div class="mt-6">
            <div class="overflow-hidden transition-all duration-300 bg-white shadow-xl rounded-2xl hover:shadow-2xl">
                <div class="relative px-6 py-5 border-b border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-r from-accent/3 to-transparent"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-accent to-amber-500"></div>
                                <h2 class="text-lg font-bold text-gray-800">Resumen de Caja</h2>
                            </div>
                            <p class="text-xs text-gray-500">Estado actual de la caja registradora</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="p-2 text-gray-400 transition-colors bg-gray-50 rounded-xl hover:bg-gray-100 hover:text-gray-600">
                                <i class="text-xs fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-green-100 rounded-xl">
                                <i class="text-xl text-green-600 fas fa-cash-register"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">Efectivo</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">${{ number_format($resumenCaja['total_cash'], 2) }}</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-blue-100 rounded-xl">
                                <i class="text-xl text-blue-600 fas fa-credit-card"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">Tarjeta</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">${{ number_format($resumenCaja['total_card'], 2) }}</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 mb-3 bg-purple-100 rounded-xl">
                                <i class="text-xl text-purple-600 fas fa-mobile-alt"></i>
                            </div>
                            <p class="text-xs tracking-wider text-gray-500 uppercase">Digital (QR/Transferencia)</p>
                            <p class="mt-1 text-2xl font-bold text-gray-800">${{ number_format($resumenCaja['total_qr'], 2) }}</p>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total en Caja (Inicial + Ventas)</p>
                                <p class="text-2xl font-bold text-primary">${{ number_format($resumenCaja['initial_amount'] + $resumenCaja['total_sales'], 2) }}</p>
                                @if($resumenCaja['difference'] != 0)
                                    <p class="text-xs text-red-500">Diferencia: ${{ number_format($resumenCaja['difference'], 2) }}</p>
                                @endif
                            </div>
                            @if($cajaAbierta)
                                <a href="{{ route('caja.cerrar', $cajaAbierta->id) }}" class="px-6 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-primary to-secondary rounded-xl hover:shadow-lg hover:scale-105">
                                    <i class="mr-2 fas fa-print"></i> Cerrar Turno
                                </a>
                            @else
                                <a href="{{ route('caja.abrir') }}" class="px-6 py-2 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-green-600 to-green-500 rounded-xl hover:shadow-lg hover:scale-105">
                                    <i class="mr-2 fas fa-cash-register"></i> Abrir Caja
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
