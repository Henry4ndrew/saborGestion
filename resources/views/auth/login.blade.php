<x-guest-layout>
    <!-- Fondo con imagen de restaurante y overlay oscuro -->
    <div class="relative flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <!-- Imagen de fondo -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1974&q=80" 
                 alt="Restaurante" 
                 class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-primary/80 to-secondary/70"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo y bienvenida -->
            <div class="mb-8 text-center animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-full shadow-lg bg-white/10 backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                    </svg>
                </div>
                <a href="/" class="inline-block">
                    <h1 class="mb-2 text-4xl font-bold text-white drop-shadow-lg">
                        SaborGestion
                    </h1>
                </a>
                <p class="text-sm text-white/90 drop-shadow">
                    Sistema de gestión gastronómica
                </p>
                <p class="mt-2 text-xs text-white/80 drop-shadow">
                    Bienvenido al control de tu restaurante
                </p>
            </div>

            <!-- Card de login -->
            <div class="overflow-hidden transition-all duration-300 shadow-2xl bg-white/95 backdrop-blur-sm rounded-2xl hover:shadow-3xl">
                <div class="px-6 py-8 sm:p-8">
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">
                        Iniciar Sesión
                    </h2>
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Campo Email -->
                        <div>
                            <x-input-label for="email" value="Correo electrónico" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <x-text-input id="email" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90"
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
                                    placeholder="chef@restaurante.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Campo Contraseña -->
                        <div>
                            <x-input-label for="password" value="Contraseña" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V6a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <x-text-input id="password" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90"
                                    type="password" 
                                    name="password" 
                                    required 
                                    placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Opciones adicionales -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox"
                                    class="transition-all duration-200 border-gray-300 rounded shadow-sm text-primary focus:ring-primary focus:ring-2"
                                    name="remember">
                                <span class="ml-2 text-sm text-gray-600 transition-colors duration-200 group-hover:text-primary">
                                    Recordarme
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium transition-colors duration-200 text-primary hover:text-primary/80"
                                   href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <!-- Botón de inicio -->
                        <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 bg-gradient-to-r from-primary to-primary/90 hover:from-primary/80 hover:to-primary rounded-xl text-white font-semibold transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Ingresar al sistema
                        </button>

                        <!-- Credenciales de prueba estilizadas -->
                        <div class="pt-4 mt-6 border-t border-gray-200">
                            <p class="mb-3 text-xs font-medium text-center text-gray-500">
                                🔑 Credenciales de prueba
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="p-2 border rounded-lg bg-amber-50 border-amber-200">
                                    <p class="font-mono text-xs font-semibold text-amber-800">Administrador</p>
                                    <p class="font-mono text-xs text-gray-600">admin@saborgestion.com</p>
                                    <p class="font-mono text-xs text-gray-500">password</p>
                                </div>
                                <div class="p-2 border border-green-200 rounded-lg bg-green-50">
                                    <p class="font-mono text-xs font-semibold text-green-800">Mesero</p>
                                    <p class="font-mono text-xs text-gray-600">mesero@saborgestion.com</p>
                                    <p class="font-mono text-xs text-gray-500">password</p>
                                </div>
                                <div class="p-2 border border-orange-200 rounded-lg bg-orange-50">
                                    <p class="font-mono text-xs font-semibold text-orange-800">Cocinero</p>
                                    <p class="font-mono text-xs text-gray-600">cocinero@saborgestion.com</p>
                                    <p class="font-mono text-xs text-gray-500">password</p>
                                </div>
                                <div class="p-2 border border-blue-200 rounded-lg bg-blue-50">
                                    <p class="font-mono text-xs font-semibold text-blue-800">Cajero</p>
                                    <p class="font-mono text-xs text-gray-600">cajero@saborgestion.com</p>
                                    <p class="font-mono text-xs text-gray-500">password</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>