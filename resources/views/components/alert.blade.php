@props(['type' => 'success', 'message' => null])

@php
    $classes = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-400 text-blue-700',
    ][$type];
    
    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
    ][$type];
@endphp

@if($message || session($type))
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
    
    <div class="{{ $classes }} border-l-4 rounded-lg shadow-lg p-4 backdrop-blur-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas {{ $icons }} text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">
                    {{ $message ?? session($type) }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false" 
                            class="inline-flex rounded-md p-1.5 focus:outline-none hover:bg-opacity-20 hover:bg-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif