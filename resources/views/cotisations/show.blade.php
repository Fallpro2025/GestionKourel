@extends('layouts.app-with-sidebar')

@section('title', 'Détails du Projet - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-project-diagram mr-3"></i>{{ $projet->nom }}</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cotisations.index') }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('cotisations.edit', $projet->id) }}" 
                       class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')
        <!-- Informations générales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Carte principale -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Informations du projet</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $projet->statut === 'actif' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                           ($projet->statut === 'termine' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                           ($projet->statut === 'suspendu' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')) }}">
                        {{ ucfirst($projet->statut) }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-white/70">Description</label>
                        <p class="text-white mt-1">{{ $projet->description ?: 'Aucune description' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Type de cotisation</label>
                            <p class="text-white mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $projet->type_cotisation === 'obligatoire' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($projet->type_cotisation === 'volontaire' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30') }}">
                                    {{ ucfirst($projet->type_cotisation) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Créé par</label>
                            <p class="text-white mt-1">{{ $projet->createur ? $projet->createur->nom . ' ' . $projet->createur->prenom : 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Date de début</label>
                            <p class="text-white mt-1">{{ $projet->date_debut->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Date de fin</label>
                            <p class="text-white mt-1">{{ $projet->date_fin->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Montant total</span>
                            <span class="font-medium text-white">{{ number_format($projet->montant_total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Montant collecté</span>
                            <span class="font-medium text-green-400">{{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Montant restant</span>
                            <span class="font-medium text-orange-400">{{ number_format($projet->montant_restant, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-white/70">Progression</span>
                            <span class="font-medium text-white">{{ $projet->pourcentage_collecte }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $projet->pourcentage_collecte }}%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/20">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-white">{{ $stats['total_assignations'] }}</p>
                                <p class="text-sm text-white/70">Assignations</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-green-400">{{ $stats['assignations_payees'] }}</p>
                                <p class="text-sm text-white/70">Payées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="ouvrirModalAjoutAssignation()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Ajouter une assignation
                    </button>
                    <button onclick="ouvrirModalPaiement()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-money-bill-wave mr-2"></i>Enregistrer un paiement
                    </button>
                    <button onclick="exporterRapport()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-file-export mr-2"></i>Exporter le rapport
                    </button>
                </div>
            </div>

            <!-- Liste des assignations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Assignations</h3>
                        <div class="flex items-center space-x-2">
                            <select id="filtreStatutAssignation" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                <option value="">Tous les statuts</option>
                                <option value="non_paye">Non payé</option>
                                <option value="partiel">Partiel</option>
                                <option value="paye">Payé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant assigné</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant payé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="listeAssignations" class="bg-white divide-y divide-gray-200">
                            @forelse($projet->assignations as $assignation)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($assignation->membre->nom, 0, 1) }}{{ substr($assignation->membre->prenom, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $assignation->membre->nom }} {{ $assignation->membre->prenom }}</div>
                                            <div class="text-sm text-gray-500">{{ $assignation->membre->telephone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($assignation->montant_assigné, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($assignation->montant_payé, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($assignation->montant_restant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $assignation->statut_paiement === 'paye' ? 'bg-green-100 text-green-800' : 
                                           ($assignation->statut_paiement === 'partiel' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $assignation->statut_paiement)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignation->date_echeance ? \Carbon\Carbon::parse($assignation->date_echeance)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="voirHistoriquePaiements({{ $assignation->id }})" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="Voir historique">
                                            <i class="fas fa-history"></i>
                                        </button>
                                        <button onclick="enregistrerPaiement({{ $assignation->id }}, '{{ $assignation->membre->nom }} {{ $assignation->membre->prenom }}')" 
                                                class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                title="Enregistrer paiement">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </button>
                                        <button onclick="marquerPaye({{ $assignation->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                                title="Marquer comme payé">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">Aucune assignation</p>
                                        <p class="text-sm">Commencez par ajouter des assignations</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajout Assignation -->
<div id="modalAjoutAssignation" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white/10 backdrop-blur-xl rounded-xl shadow-2xl w-full max-w-md border border-white/20">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Ajouter une assignation</h3>
                    <button onclick="fermerModalAjoutAssignation()" 
                            class="text-white/60 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="formAjoutAssignation" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="membre_id" class="block text-white/80 text-sm font-medium mb-2">Membre <span class="text-red-400">*</span></label>
                        <select id="membre_id" name="membre_id" required
                                class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            <option value="">Sélectionner un membre</option>
                            @foreach(\App\Models\Membre::where('statut', 'actif')->orderBy('nom')->get() as $membre)
                            <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="montant_assigné" class="block text-white/80 text-sm font-medium mb-2">Montant assigné <span class="text-red-400">*</span></label>
                        <input type="number" id="montant_assigné" name="montant_assigné" step="0.01" required
                               class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label for="date_echeance" class="block text-white/80 text-sm font-medium mb-2">Date d'échéance <span class="text-red-400">*</span></label>
                        <input type="date" id="date_echeance" name="date_echeance" required
                               class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="fermerModalAjoutAssignation()" 
                                class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Paiement -->
<div id="modalPaiement" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white/10 backdrop-blur-xl rounded-xl shadow-2xl w-full max-w-md border border-white/20">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Enregistrer un paiement</h3>
                    <button onclick="fermerModalPaiement()" 
                            class="text-white/60 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="formPaiement" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="assignation_id" class="block text-white/80 text-sm font-medium mb-2">Assignation <span class="text-red-400">*</span></label>
                        <select id="assignation_id" name="assignation_id" required
                                class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            <option value="">Sélectionner une assignation</option>
                            @foreach($projet->assignations as $assignation)
                            <option value="{{ $assignation->id }}" data-montant-restant="{{ $assignation->montant_restant }}">
                                {{ $assignation->membre->nom }} {{ $assignation->membre->prenom }} - 
                                Restant: {{ number_format($assignation->montant_restant, 0, ',', ' ') }} FCFA
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="montant" class="block text-white/80 text-sm font-medium mb-2">Montant <span class="text-red-400">*</span></label>
                        <input type="number" id="montant" name="montant" step="0.01" required
                               class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label for="methode" class="block text-white/80 text-sm font-medium mb-2">Méthode de paiement <span class="text-red-400">*</span></label>
                        <select id="methode" name="methode" required
                                class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            <option value="">Sélectionner une méthode</option>
                            <option value="espèces">Espèces</option>
                            <option value="virement">Virement</option>
                            <option value="chèque">Chèque</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-white/80 text-sm font-medium mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                  placeholder="Notes sur le paiement"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="fermerModalPaiement()" 
                                class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Variables globales
const projetId = {{ $projet->id }};

// Fonctions des modales
function ouvrirModalAjoutAssignation() {
    document.getElementById('modalAjoutAssignation').classList.remove('hidden');
}

function fermerModalAjoutAssignation() {
    document.getElementById('modalAjoutAssignation').classList.add('hidden');
    document.getElementById('formAjoutAssignation').reset();
}

function ouvrirModalPaiement() {
    document.getElementById('modalPaiement').classList.remove('hidden');
}

function fermerModalPaiement() {
    document.getElementById('modalPaiement').classList.add('hidden');
    document.getElementById('formPaiement').reset();
}

// Gestion du formulaire d'assignation
document.getElementById('formAjoutAssignation').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch(`/cotisations/${projetId}/assignations`, {
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
            fermerModalAjoutAssignation();
            location.reload();
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
            alerteSystem.error('Erreur lors de l\'ajout de l\'assignation');
        } else {
            alert('Erreur lors de l\'ajout de l\'assignation');
        }
    });
});

// Gestion du formulaire de paiement
document.getElementById('formPaiement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    const assignationId = data.assignation_id;
    
    fetch(`/assignations/${assignationId}/paiement`, {
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
            fermerModalPaiement();
            location.reload();
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
            alerteSystem.error('Erreur lors de l\'enregistrement du paiement');
        } else {
            alert('Erreur lors de l\'enregistrement du paiement');
        }
    });
});

// Fonctions d'action
function enregistrerPaiement(assignationId, nomMembre) {
    document.getElementById('assignation_id').value = assignationId;
    ouvrirModalPaiement();
}

function marquerPaye(assignationId) {
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.confirmation('Marquer cette assignation comme entièrement payée ?', function(confirmed) {
            if (confirmed) {
                executerMarquagePaye(assignationId);
            }
        });
    } else {
        if (confirm('Marquer cette assignation comme entièrement payée ?')) {
            executerMarquagePaye(assignationId);
        }
    }
}

function executerMarquagePaye(assignationId) {
    fetch(`/assignations/${assignationId}/marquer-paye`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
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
            alerteSystem.error('Erreur lors du marquage');
        } else {
            alert('Erreur lors du marquage');
        }
    });
}

function voirHistoriquePaiements(assignationId) {
    fetch(`/assignations/${assignationId}/historique`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher l'historique dans une modale ou une nouvelle page
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.info('Fonctionnalité d\'historique en cours de développement');
            } else {
                alert('Fonctionnalité d\'historique en cours de développement');
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function exporterRapport() {
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.info('Fonctionnalité d\'export en cours de développement');
    } else {
        alert('Fonctionnalité d\'export en cours de développement');
    }
}

// Filtrage des assignations
document.getElementById('filtreStatutAssignation').addEventListener('change', function() {
    const statut = this.value;
    const lignes = document.querySelectorAll('#listeAssignations tr');
    
    lignes.forEach(ligne => {
        const statutLigne = ligne.querySelector('td:nth-child(5) span')?.textContent.toLowerCase() || '';
        
        if (!statut || statutLigne.includes(statut.replace('_', ' '))) {
            ligne.style.display = '';
        } else {
            ligne.style.display = 'none';
        }
    });
});

// Fermer modales en cliquant à l'extérieur
document.getElementById('modalAjoutAssignation').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalAjoutAssignation();
    }
});

document.getElementById('modalPaiement').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalPaiement();
    }
});
</script>
@endsection
