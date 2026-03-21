<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gastronomico-light">
        <div>
            <a href="/">
                <h1 class="text-3xl font-bold text-gastronomico-primary">SaborGestion</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center text-gastronomico-primary mb-6">Iniciar Sesión</h2>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-gastronomico-primary shadow-sm focus:ring-gastronomico-primary" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gastronomico-primary" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3 bg-gastronomico-primary hover:bg-gastronomico-secondary">
                        {{ __('Iniciar Sesión') }}
                    </x-primary-button>
                </div>
                
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>Credenciales de prueba:</p>
                    <p class="text-xs">admin@saborgestion.com / password</p>
                    <p class="text-xs">mesero@saborgestion.com / password</p>
                    <p class="text-xs">cocinero@saborgestion.com / password</p>
                    <p class="text-xs">cajero@saborgestion.com / password</p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>