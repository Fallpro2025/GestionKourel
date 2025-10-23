@extends('layouts.app-with-sidebar')

@section('title', 'Test Sans Alertes Modernes')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Test Sans Alertes Modernes</h1>
    
    <button onclick="testSimple()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mb-4">
        Test Simple
    </button>
    
    <div id="resultat" class="mt-4 p-4 bg-white rounded-lg border">
        Résultat: <span id="texte">En attente...</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log('=== TEST SANS ALERTES MODERNES ===');

function testSimple() {
    console.log('Fonction testSimple appelée');
    document.getElementById('texte').textContent = '✅ JavaScript fonctionne !';
}

// Test au chargement
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé - Test sans alertes modernes');
    document.getElementById('texte').textContent = 'DOM chargé - Prêt pour les tests';
});
</script>
@endsection
