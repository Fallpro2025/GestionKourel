@extends('layouts.app-with-sidebar')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header fixe -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Modifier le Projet</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $projet->nom }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('cotisations.show', $projet->id) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="mt-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                <form id="formModifierProjet" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations générales -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Informations générales</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du projet *</label>
                                <input type="text" id="nom" name="nom" value="{{ $projet->nom }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="type_cotisation" class="block text-sm font-medium text-gray-700 mb-2">Type de cotisation *</label>
                                <select id="type_cotisation" name="type_cotisation" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                    <option value="obligatoire" {{ $projet->type_cotisation === 'obligatoire' ? 'selected' : '' }}>Obligatoire</option>
                                    <option value="volontaire" {{ $projet->type_cotisation === 'volontaire' ? 'selected' : '' }}>Volontaire</option>
                                    <option value="evenement" {{ $projet->type_cotisation === 'evenement' ? 'selected' : '' }}>Événement</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">{{ $projet->description }}</textarea>
                        </div>
                    </div>

                    <!-- Montants et dates -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Montants et dates</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="montant_total" class="block text-sm font-medium text-gray-700 mb-2">Montant total *</label>
                                <input type="number" id="montant_total" name="montant_total" value="{{ $projet->montant_total }}" step="0.01" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ $projet->date_debut->format('Y-m-d') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                                <input type="date" id="date_fin" name="date_fin" value="{{ $projet->date_fin->format('Y-m-d') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Statut du projet -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Statut du projet</h2>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                            <select id="statut" name="statut" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                <option value="planifie" {{ $projet->statut === 'planifie' ? 'selected' : '' }}>Planifié</option>
                                <option value="actif" {{ $projet->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="suspendu" {{ $projet->statut === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                <option value="termine" {{ $projet->statut === 'termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="annule" {{ $projet->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Statistiques actuelles -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Statistiques actuelles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900">{{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA</p>
                                <p class="text-sm text-blue-700">Montant collecté</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900">{{ $projet->assignations->count() }}</p>
                                <p class="text-sm text-blue-700">Assignations</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900">{{ $projet->assignations->where('statut_paiement', 'paye')->count() }}</p>
                                <p class="text-sm text-blue-700">Payées</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900">{{ $projet->pourcentage_collecte }}%</p>
                                <p class="text-sm text-blue-700">Progression</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('cotisations.show', $projet->id) }}" 
                           class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
// Gestion du formulaire
document.getElementById('formModifierProjet').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Validation des dates
    const dateDebut = new Date(data.date_debut);
    const dateFin = new Date(data.date_fin);
    
    if (dateFin <= dateDebut) {
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.warning('La date de fin doit être après la date de début');
        } else {
            alert('La date de fin doit être après la date de début');
        }
        return;
    }
    
    // Validation du montant
    if (parseFloat(data.montant_total) <= 0) {
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.warning('Le montant total doit être positif');
        } else {
            alert('Le montant total doit être positif');
        }
        return;
    }
    
    // Envoi des données
    fetch('{{ route("cotisations.update", $projet->id) }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            // Rediriger vers la page de détail du projet
            window.location.href = '{{ route("cotisations.show", $projet->id) }}';
        } else {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.error('Erreur lors de la modification du projet');
        } else {
            alert('Erreur lors de la modification du projet');
        }
    });
});
</script>
@endsection
