{{-- Composant pour les alertes toast --}}
@props(['type' => 'info', 'message' => '', 'title' => '', 'autoClose' => true, 'duration' => 3000])

<div x-data="{
    show: true,
    init() {
        if ({{ $autoClose ? 'true' : 'false' }}) {
            setTimeout(() => {
                this.show = false;
            }, {{ $duration }});
        }
    }
}" 
x-show="show" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 transform translate-x-full"
x-transition:enter-end="opacity-100 transform translate-x-0"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100 transform translate-x-0"
x-transition:leave-end="opacity-0 transform translate-x-full"
class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 p-4 {{ $type === 'success' ? 'border-green-500' : ($type === 'error' ? 'border-red-500' : ($type === 'warning' ? 'border-yellow-500' : 'border-blue-500')) }}">
    
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <div class="h-8 w-8 rounded-full flex items-center justify-center {{ $type === 'success' ? 'bg-green-100' : ($type === 'error' ? 'bg-red-100' : ($type === 'warning' ? 'bg-yellow-100' : 'bg-blue-100')) }}">
                <i class="{{ $type === 'success' ? 'fas fa-check text-green-600' : ($type === 'error' ? 'fas fa-times text-red-600' : ($type === 'warning' ? 'fas fa-exclamation-triangle text-yellow-600' : 'fas fa-info-circle text-blue-600')) }} text-sm"></i>
            </div>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h4 class="text-sm font-semibold text-gray-900">{{ $title }}</h4>
            @else
                <h4 class="text-sm font-semibold text-gray-900">
                    {{ $type === 'success' ? 'Succès' : ($type === 'error' ? 'Erreur' : ($type === 'warning' ? 'Attention' : 'Information')) }}
                </h4>
            @endif
            <p class="text-sm text-gray-600 mt-1">{{ $message }}</p>
        </div>
        <div class="ml-4 flex-shrink-0">
            <button @click="show = false" 
                    class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>
</div>
