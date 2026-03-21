<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaborGestion - Sistema de Gestión Gastronómica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-section {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gastronomico-light">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-utensils text-3xl text-gastronomico-primary mr-3"></i>
                    <h1 class="text-2xl font-bold text-gastronomico-primary">SaborGestion</h1>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard/administrador') }}" class="btn-primary">
                                <i class="fas fa-tachometer-alt mr-2"></i> Panel de Control
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">
                                <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-6">Bienvenido a SaborGestion</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                El sistema integral de gestión para restaurantes que transforma tu negocio gastronómico
            </p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-gastronomico-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                <i class="fas fa-rocket mr-2"></i> Comenzar Ahora
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gastronomico-primary mb-12">¿Qué Ofrecemos?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Inteligencia de Negocios</h3>
                    <p class="text-gray-600">
                        Dashboards personalizados por rol para tomar decisiones basadas en datos reales
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-boxes text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Gestión de Inventario</h3>
                    <p class="text-gray-600">
                        Controla tus ingredientes y productos con alertas de stock mínimo
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-list text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Toma de Pedidos</h3>
                    <p class="text-gray-600">
                        Sistema rápido y eficiente para tomar pedidos y gestionar comandas
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice-dollar text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Facturación</h3>
                    <p class="text-gray-600">
                        Genera pre-facturas, registra pagos y controla tus ventas fácilmente
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Gestión de Usuarios</h3>
                    <p class="text-gray-600">
                        Control total de usuarios con 4 roles diferentes: Administrador, Mesero, Cocinero y Cajero
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="bg-gastronomico-primary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-simple text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gastronomico-primary mb-3">Reportes y Análisis</h3>
                    <p class="text-gray-600">
                        Reportes detallados para cada área de tu restaurante
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gastronomico-primary py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">¿Listo para Optimizar tu Restaurante?</h2>
            <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
                Únete a SaborGestion y lleva tu negocio al siguiente nivel
            </p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-gastronomico-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                <i class="fas fa-arrow-right mr-2"></i> Iniciar Sesión
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gastronomico-dark text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2024 SaborGestion. Todos los derechos reservados.</p>
            <p class="text-sm mt-2">Sistema de Gestión Gastronómica</p>
        </div>
    </footer>
</body>
</html>