@extends('layouts.app-with-sidebar')

@section('title', 'Test JavaScript Simple')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Test JavaScript Simple</h1>
    
    <!-- Test 1: Bouton avec onclick -->
    <button onclick="testOnClick()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mb-4">
        Test OnClick
    </button>
    
    <!-- Test 2: Bouton avec addEventListener -->
    <button id="testEventListener" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 mb-4">
        Test EventListener
    </button>
    
    <!-- Test 3: Bouton pour tester le modal -->
    <button onclick="testModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 mb-4">
        Test Modal
    </button>
    
    <!-- Zone de résultats -->
    <div id="resultats" class="mt-4 p-4 bg-white rounded-lg border">
        <h3 class="font-bold mb-2">Résultats des tests :</h3>
        <div id="log"></div>
    </div>
    
    <!-- Modal de test -->
    <div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Modal de Test</h3>
                <p class="mb-4">Si vous voyez ceci, le modal fonctionne !</p>
                <button onclick="fermerModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Fonction pour ajouter des logs
function ajouterLog(message) {
    const log = document.getElementById('log');
    if (log) {
        log.innerHTML += '<div class="mb-1">' + new Date().toLocaleTimeString() + ': ' + message + '</div>';
    }
    console.log(message);
}

// Test 1: Fonction onclick
function testOnClick() {
    ajouterLog('✅ Test OnClick fonctionne !');
}

// Test 2: EventListener
document.addEventListener('DOMContentLoaded', function() {
    ajouterLog('✅ DOM chargé');
    
    const btn = document.getElementById('testEventListener');
    if (btn) {
        btn.addEventListener('click', function() {
            ajouterLog('✅ EventListener fonctionne !');
        });
        ajouterLog('✅ EventListener attaché');
    } else {
        ajouterLog('❌ Bouton EventListener non trouvé');
    }
});

// Test 3: Modal
function testModal() {
    ajouterLog('=== TEST MODAL ===');
    const modal = document.getElementById('testModal');
    if (modal) {
        ajouterLog('Modal trouvé: ' + modal.id);
        ajouterLog('Classes avant: ' + modal.className);
        modal.classList.remove('hidden');
        ajouterLog('Classes après: ' + modal.className);
        ajouterLog('✅ Modal ouvert');
    } else {
        ajouterLog('❌ Modal non trouvé');
    }
}

function fermerModal() {
    const modal = document.getElementById('testModal');
    if (modal) {
        modal.classList.add('hidden');
        ajouterLog('✅ Modal fermé');
    }
}

// Test initial
ajouterLog('=== SCRIPT CHARGÉ ===');
</script>
@endsection
