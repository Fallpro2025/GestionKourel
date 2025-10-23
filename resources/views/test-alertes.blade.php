@extends('layouts.app-with-sidebar')

@section('title', 'Test des Alertes Modernes')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-flask mr-3"></i>Test des Alertes Modernes</h1>
                    <span class="ml-3 px-3 py-1 bg-purple-500/20 text-purple-400 text-sm rounded-full border border-purple-500/30">
                        Test
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('roles.index') }}" 
                       class="px-4 py-2 bg-gray-500/20 text-gray-300 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux Rôles
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-8 border border-white/20">
            <h2 class="text-2xl font-bold text-white mb-6">
                <i class="fas fa-test-tube mr-2"></i>Test du Système d'Alertes
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Toasts -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white mb-4">Toasts</h3>
                    
                    <button onclick="alerteModerne.success('Opération réussie avec succès !')" 
                            class="w-full px-4 py-3 bg-green-500/20 text-green-400 rounded-xl hover:bg-green-500/30 transition-all duration-200 border border-green-500/30">
                        <i class="fas fa-check mr-2"></i>Toast de Succès
                    </button>
                    
                    <button onclick="alerteModerne.error('Une erreur s\'est produite lors de l\'opération')" 
                            class="w-full px-4 py-3 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-all duration-200 border border-red-500/30">
                        <i class="fas fa-times mr-2"></i>Toast d'Erreur
                    </button>
                    
                    <button onclick="alerteModerne.warning('Attention, cette action est irréversible')" 
                            class="w-full px-4 py-3 bg-yellow-500/20 text-yellow-400 rounded-xl hover:bg-yellow-500/30 transition-all duration-200 border border-yellow-500/30">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Toast d'Avertissement
                    </button>
                    
                    <button onclick="alerteModerne.info('Information importante à retenir')" 
                            class="w-full px-4 py-3 bg-blue-500/20 text-blue-400 rounded-xl hover:bg-blue-500/30 transition-all duration-200 border border-blue-500/30">
                        <i class="fas fa-info-circle mr-2"></i>Toast d'Information
                    </button>
                </div>
                
                <!-- Confirmations -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white mb-4">Confirmations</h3>
                    
                    <button onclick="testConfirmation()" 
                            class="w-full px-4 py-3 bg-purple-500/20 text-purple-400 rounded-xl hover:bg-purple-500/30 transition-all duration-200 border border-purple-500/30">
                        <i class="fas fa-question-circle mr-2"></i>Confirmation Simple
                    </button>
                    
                    <button onclick="testConfirmationSuppression()" 
                            class="w-full px-4 py-3 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-all duration-200 border border-red-500/30">
                        <i class="fas fa-trash mr-2"></i>Confirmation Suppression
                    </button>
                    
                    <button onclick="testConfirmationComplexe()" 
                            class="w-full px-4 py-3 bg-orange-500/20 text-orange-400 rounded-xl hover:bg-orange-500/30 transition-all duration-200 border border-orange-500/30">
                        <i class="fas fa-cogs mr-2"></i>Confirmation Complexe
                    </button>
                </div>
            </div>
            
            <!-- Test de session -->
            <div class="mt-8 pt-6 border-t border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Test des Alertes de Session</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('test-alertes') }}?type=success" 
                       class="px-4 py-3 bg-green-500/20 text-green-400 rounded-xl hover:bg-green-500/30 transition-all duration-200 border border-green-500/30 text-center">
                        <i class="fas fa-check mr-2"></i>Session Success
                    </a>
                    <a href="{{ route('test-alertes') }}?type=error" 
                       class="px-4 py-3 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-all duration-200 border border-red-500/30 text-center">
                        <i class="fas fa-times mr-2"></i>Session Error
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
function testConfirmation() {
    alerteModerne.confirmation('Voulez-vous continuer cette action ?', function(confirme) {
        if (confirme) {
            alerteModerne.success('Action confirmée !');
        } else {
            alerteModerne.info('Action annulée');
        }
    });
}

function testConfirmationSuppression() {
    alerteModerne.confirmation('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.', function(confirme) {
        if (confirme) {
            alerteModerne.success('Élément supprimé avec succès');
        } else {
            alerteModerne.warning('Suppression annulée');
        }
    });
}

function testConfirmationComplexe() {
    alerteModerne.confirmation('Cette action va modifier plusieurs éléments dans la base de données. Voulez-vous vraiment continuer ?', function(confirme) {
        if (confirme) {
            alerteModerne.success('Modifications appliquées avec succès');
        } else {
            alerteModerne.info('Modifications annulées');
        }
    });
}
</script>
@endsection