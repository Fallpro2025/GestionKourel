@extends('layouts.app-with-sidebar')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header fixe -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nouveau Projet de Cotisation</h1>
                    <p class="text-sm text-gray-600 mt-1">Créer un nouveau projet de cotisation</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('cotisations.index') }}" 
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
                <form id="formNouveauProjet" class="space-y-8">
                    @csrf
                    
                    <!-- Informations générales -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Informations générales</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du projet *</label>
                                <input type="text" id="nom" name="nom" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                       placeholder="Ex: Cotisation Magal Touba 2025">
                            </div>

                            <div>
                                <label for="type_cotisation" class="block text-sm font-medium text-gray-700 mb-2">Type de cotisation *</label>
                                <select id="type_cotisation" name="type_cotisation" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                    <option value="">Sélectionner un type</option>
                                    <option value="obligatoire">Obligatoire</option>
                                    <option value="volontaire">Volontaire</option>
                                    <option value="evenement">Événement</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                      placeholder="Description détaillée du projet de cotisation..."></textarea>
                        </div>
                    </div>

                    <!-- Montants et dates -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Montants et dates</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="montant_total" class="block text-sm font-medium text-gray-700 mb-2">Montant total *</label>
                                <input type="number" id="montant_total" name="montant_total" step="0.01" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                       placeholder="0.00">
                            </div>

                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                                <input type="date" id="date_debut" name="date_debut" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                                <input type="date" id="date_fin" name="date_fin" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Assignation des membres -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Assignation des membres</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="montant_par_membre" class="block text-sm font-medium text-gray-700 mb-2">Montant par membre *</label>
                                <input type="number" id="montant_par_membre" name="montant_par_membre" step="0.01" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                       placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de membres sélectionnés</label>
                                <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg">
                                    <span id="nombreMembres" class="text-lg font-semibold text-gray-900">0</span>
                                    <span class="text-gray-600 ml-2">membre(s)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Sélectionner les membres *</label>
                            
                            <!-- Filtres pour les membres -->
                            <div class="mb-4 flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <input type="text" id="rechercheMembre" placeholder="Rechercher un membre..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="selectionnerTous()" 
                                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                        Tout sélectionner
                                    </button>
                                    <button type="button" onclick="deselectionnerTous()" 
                                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        Tout désélectionner
                                    </button>
                                </div>
                            </div>

                            <!-- Liste des membres -->
                            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">
                                <div id="listeMembres" class="space-y-2">
                                    @foreach($membres as $membre)
                                    <label class="flex items-center p-2 hover:bg-white rounded-lg transition-colors duration-200 membre-item" 
                                           data-nom="{{ strtolower($membre->nom . ' ' . $membre->prenom) }}">
                                        <input type="checkbox" name="membres[]" value="{{ $membre->id }}" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 membre-checkbox">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $membre->nom }} {{ $membre->prenom }}</span>
                                                    <span class="text-sm text-gray-500 ml-2">{{ $membre->telephone }}</span>
                                                </div>
                                                <span class="text-xs text-gray-400">{{ $membre->profession }}</span>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Résumé du projet</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900" id="resumeMontantTotal">0 FCFA</p>
                                <p class="text-sm text-blue-700">Montant total</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900" id="resumeMontantParMembre">0 FCFA</p>
                                <p class="text-sm text-blue-700">Par membre</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-900" id="resumeNombreMembres">0</p>
                                <p class="text-sm text-blue-700">Membres</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('cotisations.index') }}" 
                           class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Créer le projet
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
// Variables globales
let membres = @json($membres);

// Gestion du formulaire
document.getElementById('formNouveauProjet').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Vérifier qu'au moins un membre est sélectionné
    const membresSelectionnes = formData.getAll('membres[]');
    if (membresSelectionnes.length === 0) {
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.warning('Veuillez sélectionner au moins un membre');
        } else {
            alert('Veuillez sélectionner au moins un membre');
        }
        return;
    }
    
    data.membres = membresSelectionnes;
    
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
    
    if (parseFloat(data.montant_par_membre) <= 0) {
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.warning('Le montant par membre doit être positif');
        } else {
            alert('Le montant par membre doit être positif');
        }
        return;
    }
    
    // Envoi des données
    fetch('{{ route("cotisations.store") }}', {
        method: 'POST',
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
            window.location.href = `/cotisations/${data.projet_id}`;
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
            alerteSystem.error('Erreur lors de la création du projet');
        } else {
            alert('Erreur lors de la création du projet');
        }
    });
});

// Fonctions de sélection des membres
function selectionnerTous() {
    const checkboxes = document.querySelectorAll('.membre-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    mettreAJourResume();
}

function deselectionnerTous() {
    const checkboxes = document.querySelectorAll('.membre-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    mettreAJourResume();
}

// Recherche de membres
document.getElementById('rechercheMembre').addEventListener('input', function() {
    const recherche = this.value.toLowerCase();
    const membres = document.querySelectorAll('.membre-item');
    
    membres.forEach(membre => {
        const nom = membre.getAttribute('data-nom');
        if (nom.includes(recherche)) {
            membre.style.display = '';
        } else {
            membre.style.display = 'none';
        }
    });
});

// Mise à jour du résumé
function mettreAJourResume() {
    const montantTotal = parseFloat(document.getElementById('montant_total').value) || 0;
    const montantParMembre = parseFloat(document.getElementById('montant_par_membre').value) || 0;
    const membresSelectionnes = document.querySelectorAll('.membre-checkbox:checked').length;
    
    document.getElementById('resumeMontantTotal').textContent = formatMontant(montantTotal);
    document.getElementById('resumeMontantParMembre').textContent = formatMontant(montantParMembre);
    document.getElementById('resumeNombreMembres').textContent = membresSelectionnes;
    document.getElementById('nombreMembres').textContent = membresSelectionnes;
}

// Formatage des montants
function formatMontant(montant) {
    return new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
}

// Event listeners pour la mise à jour du résumé
document.getElementById('montant_total').addEventListener('input', mettreAJourResume);
document.getElementById('montant_par_membre').addEventListener('input', mettreAJourResume);

// Event listeners pour les checkboxes
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('membre-checkbox')) {
        mettreAJourResume();
    }
});

// Calcul automatique du montant total
document.getElementById('montant_par_membre').addEventListener('input', function() {
    const montantParMembre = parseFloat(this.value) || 0;
    const membresSelectionnes = document.querySelectorAll('.membre-checkbox:checked').length;
    
    if (membresSelectionnes > 0) {
        const montantTotal = montantParMembre * membresSelectionnes;
        document.getElementById('montant_total').value = montantTotal.toFixed(2);
        mettreAJourResume();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    mettreAJourResume();
});
</script>
@endsection
