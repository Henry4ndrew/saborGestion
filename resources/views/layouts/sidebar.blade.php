@php
    $user = Auth::user();
    $role = $user->role;
@endphp

<aside class="w-64 bg-white shadow-lg">
    <div class="p-4 border-b">
        <h1 class="text-2xl font-bold text-gastronomico-primary">SaborGestion</h1>
        <p class="text-sm text-gray-600">{{ ucfirst($role) }}</p>
    </div>
    
    <nav class="p-4 space-y-2">
        <!-- Inteligencia de Negocios -->
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Inteligencia de Negocios</h3>
            <div class="space-y-1">
                @if($role == 'admin')
                    <a href="{{ route('dashboard.administrador') }}" class="sidebar-link">
                        <i class="fas fa-chart-line w-5 mr-2"></i> Dashboard Administrador
                    </a>
                @endif
                @if($role == 'mesero')
                    <a href="{{ route('dashboard.mesero') }}" class="sidebar-link">
                        <i class="fas fa-chart-line w-5 mr-2"></i> Dashboard Mesero
                    </a>
                @endif
                @if($role == 'cocinero')
                    <a href="{{ route('dashboard.cocinero') }}" class="sidebar-link">
                        <i class="fas fa-chart-line w-5 mr-2"></i> Dashboard Cocinero
                    </a>
                @endif
                @if($role == 'cajero')
                    <a href="{{ route('dashboard.cajero') }}" class="sidebar-link">
                        <i class="fas fa-chart-line w-5 mr-2"></i> Dashboard Cajero
                    </a>
                @endif
            </div>
        </div>

        <!-- Gestión de catálogo y menú -->
        @if(in_array($role, ['admin', 'cocinero']))
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gestión de catálogo y menú</h3>
            <div class="space-y-1">
                <a href="{{ route('productos.index') }}" class="sidebar-link">
                    <i class="fas fa-utensils w-5 mr-2"></i> Gestión de Productos
                </a>
                <a href="{{ route('inventario.index') }}" class="sidebar-link">
                    <i class="fas fa-boxes w-5 mr-2"></i> Inventario Ingredientes
                    @php
                        try {
                            $stockBajo = \App\Models\Inventario::whereRaw('cantidad <= stock_minimo')->count();
                        } catch (\Exception $e) {
                            $stockBajo = 0;
                        }
                    @endphp
                    @if(isset($stockBajo) && $stockBajo > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $stockBajo }}</span>
                    @endif
                </a>
            </div>
        </div>
        @endif

        @if(in_array($role, ['admin', 'mesero']))
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gestión de catálogo y menú</h3>
            <div class="space-y-1">
                <a href="{{ route('mesas.index') }}" class="sidebar-link">
                    <i class="fas fa-chair w-5 mr-2"></i> Gestión de Mesas
                </a>
            </div>
        </div>
        @endif

        <!-- Módulo de Atención y Pedidos -->
        @if(in_array($role, ['admin', 'cajero']))
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Módulo de Atención y Pedidos</h3>
            <div class="space-y-1">
                <a href="{{ route('pedidos.index') }}" class="sidebar-link">
                    <i class="fas fa-clipboard-list w-5 mr-2"></i> Toma de pedidos
                </a>
                <a href="{{ route('comandas.index') }}" class="sidebar-link">
                    <i class="fas fa-receipt w-5 mr-2"></i> Gestión de comanda
                </a>
                <a href="{{ route('delivery.index') }}" class="sidebar-link">
                    <i class="fas fa-motorcycle w-5 mr-2"></i> Atención Delivery
                </a>
            </div>
        </div>

        <!-- Facturación y Cierre -->
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Facturación y Cierre</h3>
            <div class="space-y-1">
                <a href="{{ route('facturas.index') }}" class="sidebar-link">
                    <i class="fas fa-file-invoice w-5 mr-2"></i> Generación de Pre-factura
                </a>
                <a href="{{ route('pagos.index') }}" class="sidebar-link">
                    <i class="fas fa-credit-card w-5 mr-2"></i> Registro de Pagos
                </a>
                <a href="{{ route('cierres.index') }}" class="sidebar-link">
                    <i class="fas fa-cash-register w-5 mr-2"></i> Cierre de Caja
                </a>
            </div>
        </div>
        @endif

        <!-- Usuarios (Solo Administrador) -->
        @if($role == 'admin')
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administración</h3>
            <div class="space-y-1">
                <a href="{{ route('usuarios.index') }}" class="sidebar-link">
                    <i class="fas fa-users w-5 mr-2"></i> Usuarios
                </a>
            </div>
        </div>
        @endif
    </nav>
    
    <div class="absolute bottom-0 w-64 p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-left">
                <i class="fas fa-sign-out-alt w-5 mr-2"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>