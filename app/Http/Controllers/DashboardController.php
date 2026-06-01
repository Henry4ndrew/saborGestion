<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Pedido;
use App\Models\Factura;
use App\Models\User;
use App\Models\Plato;
use App\Models\Ingrediente;
use App\Models\Inventario;
use App\Models\CashClosure;
use App\Models\DetallePedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Services\DashboardAnalyticsService;

class DashboardController extends Controller
{
    protected $analytics;

    public function __construct(DashboardAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }
    public function cajeroIndex()
    {
        $hoy = Carbon::today();

        // 1. Pedidos entregados por mesero (listos para cobrar)
        $pedidosEntregados = Pedido::with(['mesa', 'usuario', 'detalles.plato'])
            ->where('estado', 'entregado')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 2. Ventas totales del día (facturas pagadas)
        $ventasHoy = Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $hoy)
            ->sum('total');

        // 3. Cantidad de pedidos cobrados hoy (facturas pagadas)
        $pedidosCobradosHoy = Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $hoy)
            ->count();

        // 4. Pedidos pendientes de pago (entregados sin factura pagada)
        $pedidosPendientesPago = Pedido::where('estado', 'entregado')
            ->whereDoesntHave('factura', function ($q) {
                $q->where('estado', 'pagada');
            })
            ->count();

        // 5. Ingresos agrupados por método de pago (facturas pagadas hoy)
        $ingresosPorMetodo = Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $hoy)
            ->select('metodo_pago', DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        // 6. Total de facturas emitidas hoy (pagadas + pendientes)
        $facturasEmitidasHoy = Factura::whereDate('fecha_emision', $hoy)->count();

        // 7. Últimos 10 pagos realizados
        $ultimosPagos = Factura::with('pedido', 'usuario')
            ->where('estado', 'pagada')
            ->orderBy('fecha_emision', 'desc')
            ->limit(10)
            ->get();

        // 8. Caja abierta para el usuario actual
        $cajaAbierta = CashClosure::where('user_id', auth()->id())
            ->where('status', 'Open')
            ->first();

        // 9. Resumen de movimientos de caja (si está abierta, se toman sus valores; si no, se calculan desde facturas)
        $resumenCaja = [];
        if ($cajaAbierta) {
            $resumenCaja = [
                'initial_amount' => $cajaAbierta->initial_amount,
                'total_sales'    => $cajaAbierta->total_sales ?? 0,
                'total_cash'     => $cajaAbierta->total_cash ?? 0,
                'total_card'     => $cajaAbierta->total_card ?? 0,
                'total_qr'       => ($cajaAbierta->total_qr ?? 0) + ($cajaAbierta->total_transferencia ?? 0),
                'difference'     => $cajaAbierta->difference ?? 0,
            ];
        } else {
            $resumenCaja = [
                'initial_amount' => 0,
                'total_sales'    => $ventasHoy,
                'total_cash'     => $ingresosPorMetodo['efectivo'] ?? 0,
                'total_card'     => $ingresosPorMetodo['tarjeta'] ?? 0,
                'total_qr'       => ($ingresosPorMetodo['qr'] ?? 0) + ($ingresosPorMetodo['transferencia'] ?? 0),
                'difference'     => 0,
            ];
        }

        // 10. Productos más vendidos del día (según pedidos entregados o facturados)
        $productosMasVendidos = DetallePedido::select(
                'platos.id',
                'platos.nombre',
                DB::raw('SUM(detalle_pedidos.cantidad) as total_vendido')
            )
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->join('platos', 'detalle_pedidos.plato_id', '=', 'platos.id')
            ->whereIn('pedidos.estado', ['entregado', 'facturado'])
            ->whereDate('pedidos.updated_at', $hoy)
            ->groupBy('platos.id', 'platos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        // 11. Ticket promedio del día
        $ticketPromedio = $pedidosCobradosHoy > 0
            ? $ventasHoy / $pedidosCobradosHoy
            : 0;

        // 12. Productos agotados (stock actual <= stock mínimo)
        $productosAgotados = Inventario::whereRaw('cantidad_actual <= stock_minimo')->count();

        // 13. Clientes atendidos (número de facturas pagadas hoy, puede ajustarse a clientes únicos)
        $clientesAtendidos = Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $hoy)
            ->count(); // Cambiar a distinct('cliente_nombre') si se requiere unicidad

        return view('dashboard.cajero.index', compact(
            'pedidosEntregados',
            'ventasHoy',
            'pedidosCobradosHoy',
            'pedidosPendientesPago',
            'ingresosPorMetodo',
            'facturasEmitidasHoy',
            'ultimosPagos',
            'cajaAbierta',
            'resumenCaja',
            'productosMasVendidos',
            'ticketPromedio',
            'productosAgotados',
            'clientesAtendidos'
        ));
    }


    public function administrador()
    {
        $this->authorizeRole('admin');

        $alerts = $this->analytics->getAlerts();
        $summary = $this->analytics->getGeneralSummary();
        $salesPerDay = $this->analytics->getSalesPerDay();
        $salesPerMonth = $this->analytics->getSalesPerMonth();
        $topProducts = $this->analytics->getTopProducts();
        $paymentMethods = $this->analytics->getPaymentMethods();
        $recentActivity = $this->analytics->getRecentActivity();

        return view('admin.analytics', compact(
            'alerts', 'summary', 'salesPerDay', 'salesPerMonth',
            'topProducts', 'paymentMethods', 'recentActivity'
        ));
    }

    public function mesero()
    {
        $this->authorizeRole('mesero');

        // Pedidos activos del mesero: pendiente, en preparación y listos para entregar
        $pedidos = \App\Models\Pedido::where('usuario_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('estado', [
                \App\Models\Pedido::ESTADO_PENDIENTE,
                \App\Models\Pedido::ESTADO_EN_PREPARACION,
                \App\Models\Pedido::ESTADO_LISTO,
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pedidos que el mesero ya entregó al cajero
        $pedidosFinalizados = \App\Models\Pedido::where('usuario_id', \Illuminate\Support\Facades\Auth::id())
            ->where('estado', \App\Models\Pedido::ESTADO_ENTREGADO)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.mesero.index', compact('pedidos', 'pedidosFinalizados'));
    }

    public function cocinero()
    {
        $this->authorizeRole('cocinero');

        // Mostrar pedidos de hoy: En espera (pendiente) y En preparación
        $query = \App\Models\Pedido::with(['detalles.plato', 'mesa', 'usuario'])
            ->whereDate('created_at', now()->today())
            ->whereIn('estado', [
                \App\Models\Pedido::ESTADO_PENDIENTE,
                \App\Models\Pedido::ESTADO_EN_PREPARACION,
            ]);

        $comandas = $query->orderBy('created_at', 'asc')->paginate(12);

        $tipos = \App\Models\Pedido::getTipos();

        // Estadísticas solo de HOY
        $stats = [
            'total'          => \App\Models\Pedido::whereDate('created_at', now()->today())
                                    ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])->count(),
            'pendientes'     => \App\Models\Pedido::whereDate('created_at', now()->today())
                                    ->where('estado', 'pendiente')->count(),
            'en_preparacion' => \App\Models\Pedido::whereDate('created_at', now()->today())
                                    ->where('estado', 'en_preparacion')->count(),
            'listos'         => \App\Models\Pedido::whereDate('created_at', now()->today())
                                    ->where('estado', 'listo')->count(),
        ];

        return view('dashboard.cocinero.index', compact('comandas', 'tipos', 'stats'));
    }

    public function cajero()
    {
        $this->authorizeRole('cajero');

        // Auto-fix: Si hay pedidos entregados que ya tienen factura pagada, moverlos a facturado
        \App\Models\Pedido::where('estado', \App\Models\Pedido::ESTADO_ENTREGADO)
            ->whereHas('factura', function ($query) {
                $query->where('estado', \App\Models\Factura::ESTADO_PAGADA);
            })
            ->update(['estado' => \App\Models\Pedido::ESTADO_FACTURADO]);

        return $this->cajeroIndex();
    }

    public function cliente()
    {
        $this->authorizeRole('cliente');
        return view('dashboard.cliente.index');
    }

    private function authorizeRole($role)
    {
        if (Auth::user()->role !== $role && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    }
}
