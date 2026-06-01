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

        // Todos los pedidos que el mesero marcó como entregados
        $pedidosEntregados = \App\Models\Pedido::with(['usuario', 'mesa', 'detalles.plato'])
            ->where('estado', \App\Models\Pedido::ESTADO_ENTREGADO)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.cajero.index', compact('pedidosEntregados'));
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
