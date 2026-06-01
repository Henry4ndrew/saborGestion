@extends('layouts.app')

@section('title', 'Abrir Caja')

@section('content')
<div class="">
    <div class="">

        {{-- Header Section --}}
       <div class="flex items-end justify-between pb-6 mb-8 border-b border-border/50">
    <div>
        <span class="text-[10px] font-bold uppercase tracking-widest text-primary">Gestión de Efectivo</span>
        <h1 class="text-3xl font-bold text-text">Nueva Apertura de Caja</h1>
    </div>

    <a href="{{ route('caja.index') }}"
       class="text-sm font-semibold transition-colors text-primary hover:text-primary/80">
        &larr; Historial
    </a>
</div>

        {{-- Card Container --}}
        <div class="overflow-hidden border shadow-sm bg-surface border-border/50 rounded-2xl">

            {{-- Formulario --}}
            <div class="p-6 md:p-8">

                {{-- Alertas (Diseño integrado) --}}
                @if(session('error'))
                    <div class="p-4 mb-6 text-sm text-red-700 border-l-4 border-red-500 rounded bg-red-50">
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('caja.store') }}" id="cashRegisterForm">
                    @csrf

                    <div class="mb-6">
                        <label for="initial_amount" class="block mb-2 text-xs font-bold tracking-wider uppercase text-muted">
                            Monto inicial ($) <span class="text-primary">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="initial_amount" id="initial_amount" step="0.01" min="0"
                                   value="{{ old('initial_amount') }}"
                                   class="w-full py-3 pl-4 pr-4 font-mono text-lg transition-all border outline-none bg-background border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl"
                                   placeholder="0.00" required>
                        </div>
                        @error('initial_amount')
                            <p class="mt-2 text-xs font-medium text-primary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="observations" class="block mb-2 text-xs font-bold tracking-wider uppercase text-muted">
                            Observaciones (Opcional)
                        </label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="w-full p-4 transition-all border outline-none bg-background border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl"
                                  placeholder="Detalles sobre el turno...">{{ old('observations') }}</textarea>
                    </div>

                    {{-- Info Box --}}
                    <div class="flex items-start gap-3 p-4 mb-8 border bg-primary/5 border-primary/10 rounded-xl">
                        <div class="mt-0.5 text-primary">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 001-1v-4a1 1 0 00-2 0v4a1 1 0 001 1z"/></svg>
                        </div>
                        <p class="text-xs leading-relaxed text-muted">
                            Asegúrese de verificar el efectivo físico. La caja permanecerá activa hasta el proceso de cierre.
                        </p>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex gap-3">
                        <a href="{{ url('/dashboard') }}"
                           class="flex-1 px-5 py-3 text-sm font-bold text-center transition-colors bg-gray-100 text-muted rounded-xl hover:bg-gray-200">
                            Cancelar
                        </a>
                        <button type="submit" id="submitButton"
                                class="flex-[2] px-5 py-3 text-sm font-bold text-center text-white bg-primary rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all active:scale-95">
                            Abrir caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('cashRegisterForm');
    const submitBtn = document.getElementById('submitButton');
    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Procesando...';
        submitBtn.classList.add('opacity-75');
    });
</script>
@endsection
