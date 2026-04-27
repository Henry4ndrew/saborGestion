<!-- Alertas desde la sesión -->
<x-alert type="success" />
<x-alert type="error" />
<x-alert type="warning" />
<x-alert type="info" />

<!-- Si quieres mostrar errores de validación -->
@if($errors->any())
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform -translate-y-2"
     class="fixed top-4 right-4 z-50 w-full max-w-sm">
    
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium mb-1">Errores de validación:</p>
                <ul class="text-xs list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="ml-auto pl-3">
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif