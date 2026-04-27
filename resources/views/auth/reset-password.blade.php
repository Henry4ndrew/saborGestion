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

            <!-- Card de reset password -->
            <div class="overflow-hidden transition-all duration-300 shadow-2xl bg-white/95 backdrop-blur-sm rounded-2xl hover:shadow-3xl">
                <div class="px-6 py-8 sm:p-8">
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">
                        Restablecer Contraseña
                    </h2>
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
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
                                    :value="old('email', $request->email)" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    placeholder="chef@restaurante.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" value="Nueva contraseña" class="mb-1 text-sm font-semibold text-gray-700" />
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
                                    autocomplete="new-password"
                                    placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" value="Confirmar contraseña" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V6a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <x-text-input id="password_confirmation" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90"
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Botón de reset -->
                        <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 bg-gradient-to-r from-primary to-primary/90 hover:from-primary/80 hover:to-primary rounded-xl text-white font-semibold transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg hover:shadow-xl">
                            Restablecer contraseña
                        </button>

                        <!-- Enlace para volver al login -->
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                ¿Recordaste tu contraseña?
                                <a href="{{ route('login') }}"
                                class="font-semibold text-primary hover:text-primary/80 transition-colors">
                                    Iniciar sesión
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>