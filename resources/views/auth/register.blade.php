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
                    Regístrate y sé nuestro cliente VIP
                </p>
            </div>


            <!-- Card de registro -->
            <div class="overflow-hidden transition-all duration-300 shadow-2xl bg-white/95 backdrop-blur-sm rounded-2xl hover:shadow-3xl">
                <div class="px-6 py-8 sm:p-8">
                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">
                        Crear cuenta
                    </h2>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Nombre -->
                        <div>
                            <x-input-label for="name" :value="__('Nombre completo')" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="text-gray-400 fas fa-user"></i>
                                </div>
                                <x-text-input id="name" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Ej: Juan Pérez" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-500" />
                        </div>

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
                                    autocomplete="username"
                                    placeholder="chef@restaurante.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <x-input-label for="password" :value="__('Contraseña')" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="text-gray-400 fas fa-lock"></i>
                                </div>
                                <x-text-input id="password" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Confirmar contraseña -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <x-text-input id="password_confirmation" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white/90"
                                    type="password"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Repite tu contraseña" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-500" />
                        </div>

                        <!-- Celular -->
                        <div>
                            <x-input-label for="celular" value="Celular" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="text-gray-400 fas fa-phone"></i>
                                </div>
                                <x-text-input id="celular" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary bg-white/90"
                                    type="text" 
                                    name="celular"
                                    :value="old('celular')"
                                    placeholder="Ej: 70012345" />
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <x-input-label for="direccion" value="Dirección" class="mb-1 text-sm font-semibold text-gray-700" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="text-gray-400 fas fa-map-marker-alt"></i>
                                </div>
                                <x-text-input id="direccion" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary bg-white/90"
                                    type="text" 
                                    name="direccion"
                                    :value="old('direccion')"
                                    placeholder="Ej: Av. América #123" />
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col items-center justify-between gap-4 mt-6 sm:flex-row">
                            <a class="w-full px-4 py-2 text-sm font-medium text-center transition-colors duration-200 rounded-lg sm:w-auto text-primary hover:text-primary/80 hover:bg-primary/5" 
                               href="{{ route('login') }}">
                                ¿Ya tienes cuenta? Inicia sesión
                            </a>

                            <button type="submit"
                                class="w-full sm:w-auto flex justify-center items-center px-6 py-2.5 bg-gradient-to-r from-primary to-primary/90 hover:from-primary/80 hover:to-primary rounded-xl text-white font-semibold transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Registrarse
                            </button>
                        </div>

                        <!-- Mensaje informativo -->
                        <div class="pt-3 mt-4 border-t border-gray-200">
                            <p class="text-xs text-center text-gray-500">
                                Al registrarte aceptas nuestros términos y condiciones
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>