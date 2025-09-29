{{-- Composant pour les modales de confirmation --}}
@props(['id' => 'confirmation-modal', 'title' => 'Confirmation', 'message' => '', 'confirmText' => 'Confirmer', 'cancelText' => 'Annuler', 'type' => 'warning'])

<div x-data="{
    show: false,
    open() {
        this.show = true;
        document.body.classList.add('overflow-hidden');
    },
    close(confirmed = false) {
        this.show = false;
        document.body.classList.remove('overflow-hidden');
        if (this.callback) {
            this.callback(confirmed);
        }
    },
    callback: null
}" 
x-show="show" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
@keydown.escape.window="close(false)"
class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 z-50"
id="{{ $id }}">
    
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $type === 'success' ? 'bg-green-100' : ($type === 'error' ? 'bg-red-100' : ($type === 'warning' ? 'bg-yellow-100' : 'bg-blue-100')) }} mr-3">
                    <i class="{{ $type === 'success' ? 'fas fa-check text-green-600' : ($type === 'error' ? 'fas fa-times text-red-600' : ($type === 'warning' ? 'fas fa-exclamation-triangle text-yellow-600' : 'fas fa-info-circle text-blue-600')) }} text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            </div>
            
            <p class="text-gray-600 mb-6">{{ $message }}</p>
            
            <div class="flex space-x-3 justify-end">
                <button @click="close(false)" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    {{ $cancelText }}
                </button>
                <button @click="close(true)" 
                        class="px-4 py-2 text-sm font-medium text-white {{ $type === 'success' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : ($type === 'error' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : ($type === 'warning' ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500')) }} rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction globale pour ouvrir la modale de confirmation
function openConfirmationModal(id, message, callback, options = {}) {
    const modal = document.getElementById(id);
    if (modal && modal._x_dataStack) {
        const data = modal._x_dataStack[0];
        data.callback = callback;
        data.open();
    }
}

// Fonction globale pour fermer la modale de confirmation
function closeConfirmationModal(id, confirmed = false) {
    const modal = document.getElementById(id);
    if (modal && modal._x_dataStack) {
        const data = modal._x_dataStack[0];
        data.close(confirmed);
    }
}
</script>
