@extends('layouts.app-with-sidebar')

@section('title', 'Test Modal Rôles')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Test Modal Ajout Rôle</h1>
    
    <!-- Bouton de test -->
    <button onclick="ouvrirModalAjoutRole()" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            id="btnAjouterRole">
        Ajouter un Rôle
    </button>
    
    <!-- Modal de test -->
    <div id="modalAjoutRole" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Ajouter un Rôle</h3>
                    <button onclick="fermerModalAjoutRole()" class="text-gray-400 hover:text-gray-600">
                        ×
                    </button>
                </div>
                <p>Modal de test - Si vous voyez ceci, le modal fonctionne !</p>
                <div class="mt-4">
                    <button onclick="fermerModalAjoutRole()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log('=== TEST MODAL CHARGÉ ===');

// Test au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CHARGÉ ===');
    
    const btnAjouter = document.getElementById('btnAjouterRole');
    console.log('Bouton ajouter trouvé:', btnAjouter);
    
    const modal = document.getElementById('modalAjoutRole');
    console.log('Modal trouvé:', modal);
    
    if (btnAjouter && modal) {
        console.log('✅ Tout est prêt pour l\'ajout de rôle');
    } else {
        console.error('❌ Problème de chargement des éléments');
    }
});

// Ouvrir modal d'ajout de rôle
function ouvrirModalAjoutRole() {
    console.log('=== OUVRIR MODAL AJOUT RÔLE ===');
    console.log('Fonction appelée');
    
    const modal = document.getElementById('modalAjoutRole');
    console.log('Modal trouvé:', modal);
    
    if (modal) {
        console.log('Classes avant:', modal.className);
        modal.classList.remove('hidden');
        console.log('Classes après:', modal.className);
        console.log('Modal ouvert avec succès');
    } else {
        console.error('Modal non trouvé !');
        alert('Erreur: Modal non trouvé');
    }
}

// Fermer modal d'ajout de rôle
function fermerModalAjoutRole() {
    console.log('=== FERMER MODAL ===');
    const modal = document.getElementById('modalAjoutRole');
    if (modal) {
        modal.classList.add('hidden');
        console.log('Modal fermé');
    }
}
</script>
@endsection
