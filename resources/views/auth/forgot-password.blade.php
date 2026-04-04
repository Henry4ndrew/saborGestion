<x-guest-layout>
    <!-- Fondo con imagen de restaurante y overlay cálido -->
    <div class="relative flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <!-- Imagen de fondo de restaurante -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" 
                 alt="Restaurante elegante" 
                 class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/85 via-primary/75 to-secondary/65"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo y bienvenida -->
            <div class="mb-8 text-center animate-fade-in">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg bg-white/10 backdrop-blur-sm">
                        <img src="{{ asset('logo.png') }}" 
                            alt="SaborGestion Logo" 
                            class="object-cover w-14 h-14 rounded-full">
                    </div>

                    <a href="/" class="inline-block">
                        <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-lg leading-none">
                            Sabor Gestión
                        </h1>
                    </a>
                </div>
                <p class="text-sm text-white/90 drop-shadow">
                    Sistema de gestión gastronómica
                </p>
            </div>

            <!-- Card de recuperación -->
            <div class="overflow-hidden transition-all duration-300 shadow-2xl bg-white/95 backdrop-blur-sm rounded-2xl hover:shadow-3xl">
                <div class="px-6 py-8 sm:p-8">
                    <h2 class="mb-2 text-2xl font-bold text-center text-gray-800">
                        Recuperar Contraseña
                    </h2>
                    
                    <p class="mb-6 text-sm text-center text-gray-600">
                        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Correo electrónico')" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="text-gray-400 fas fa-envelope"></i>
                                </div>
                                <x-text-input id="email" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    placeholder="chef@restaurante.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col items-center justify-between gap-4 mt-6 sm:flex-row">
                            <a class="w-full px-4 py-2 text-sm font-medium text-center transition-colors duration-200 rounded-lg sm:w-auto text-primary hover:text-primary/80 hover:bg-primary/5" 
                               href="{{ route('login') }}">
                                ← Volver al login
                            </a>

                            <button type="submit"
                                class="w-full sm:w-auto flex justify-center items-center px-6 py-2.5 bg-gradient-to-r from-primary to-primary/90 hover:from-primary/80 hover:to-primary rounded-xl text-white font-semibold transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Enviar enlace
                            </button>
                        </div>

                        <!-- Mensaje informativo -->
                        <div class="pt-3 mt-4 border-t border-gray-200">
                            <p class="text-xs text-center text-gray-500">
                                ¿Necesitas ayuda? Contacta con soporte
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>