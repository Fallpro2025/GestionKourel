@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Cotisations - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-money-bill-wave mr-3"></i>Gestion des Cotisations</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <button onclick="exporterProjets('pdf')" 
                                class="px-3 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30"
                                title="Exporter en PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button onclick="exporterProjets('excel')" 
                                class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30"
                                title="Exporter en Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button onclick="exporterProjets('csv')" 
                                class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30"
                                title="Exporter en CSV">
                            <i class="fas fa-file-csv"></i>
                        </button>
                        <button onclick="ouvrirModalNouveauProjet()" 
                                class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-green-500/25">
                            <i class="fas fa-plus mr-2"></i>Nouveau Projet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')
        <!-- Statistiques avancées -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total collecté -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Collecté</p>
                        <p class="text-white text-2xl font-bold">{{ number_format($stats['montant_total_collecte'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-400 font-medium">+{{ $stats['taux_recouvrement'] }}%</span>
                    <span class="text-white/60 ml-2">taux recouvrement</span>
                </div>
            </div>

            <!-- Taux recouvrement -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Taux Recouvrement</p>
                        <p class="text-white text-2xl font-bold">{{ $stats['taux_recouvrement'] }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-blue-400 font-medium">Objectif: 100%</span>
                </div>
            </div>

            <!-- Projets actifs -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Projets Actifs</p>
                        <p class="text-white text-2xl font-bold">{{ $stats['projets_actifs'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-purple-400 font-medium">{{ $stats['total_projets'] }} total</span>
                </div>
            </div>

            <!-- En retard -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">En Retard</p>
                        <p class="text-white text-2xl font-bold">{{ $stats['assignations_en_retard'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-red-400 font-medium">À suivre</span>
                </div>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 mb-6 border border-white/20">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="relative">
                        <input type="text" id="rechercheProjet" placeholder="Rechercher un projet..." 
                               class="w-full md:w-64 pl-10 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/60">
                        <i class="fas fa-search absolute left-3 top-4 text-white/60"></i>
                    </div>
                    
                    <select id="filtreStatut" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les statuts</option>
                        <option value="planifie" class="text-gray-800">Planifié</option>
                        <option value="actif" class="text-gray-800">Actif</option>
                        <option value="suspendu" class="text-gray-800">Suspendu</option>
                        <option value="termine" class="text-gray-800">Terminé</option>
                        <option value="annule" class="text-gray-800">Annulé</option>
                    </select>

                    <select id="filtreType" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les types</option>
                        <option value="obligatoire" class="text-gray-800">Obligatoire</option>
                        <option value="volontaire" class="text-gray-800">Volontaire</option>
                        <option value="evenement" class="text-gray-800">Événement</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <button onclick="reinitialiserFiltres()" 
                            class="px-4 py-2 text-white/70 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Effacer
                    </button>
                </div>
            </div>
        </div>

        <!-- Liste des projets -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Projets de Cotisation</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Projet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Collecté</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Progression</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Échéance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="listeProjets" class="bg-white/5 divide-y divide-white/20">
                        @forelse($projets as $projet)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $projet->nom }}</div>
                                    <div class="text-sm text-white/60">{{ Str::limit($projet->description, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $projet->type_cotisation === 'obligatoire' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($projet->type_cotisation === 'volontaire' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30') }}">
                                    {{ ucfirst($projet->type_cotisation) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ number_format($projet->montant_total, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ number_format($projet->montant_collecte, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-white/20 rounded-full h-2 mr-2">
                                        <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $projet->pourcentage_collecte }}%"></div>
                                    </div>
                                    <span class="text-sm text-white/70">{{ $projet->pourcentage_collecte }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $projet->statut === 'actif' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($projet->statut === 'termine' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($projet->statut === 'suspendu' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')) }}">
                                    {{ ucfirst($projet->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $projet->date_fin->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('cotisations.show', $projet->id) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cotisations.edit', $projet->id) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="supprimerProjet({{ $projet->id }}, '{{ $projet->nom }}')" 
                                            class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Aucun projet de cotisation</p>
                                    <p class="text-sm">Commencez par créer votre premier projet</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($projets->hasPages())
            <div class="px-6 py-4 border-t border-white/20">
                {{ $projets->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal Nouveau Projet -->
<div id="modalNouveauProjet" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-2xl border border-white/20">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Nouveau Projet de Cotisation</h3>
                    <button onclick="fermerModalNouveauProjet()" 
                            class="text-white/60 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="formNouveauProjet" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-white/80 text-sm font-medium mb-2">Nom du projet <span class="text-red-400">*</span></label>
                            <input type="text" id="nom" name="nom" required
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Nom du projet">
                        </div>

                        <div>
                            <label for="type_cotisation" class="block text-white/80 text-sm font-medium mb-2">Type <span class="text-red-400">*</span></label>
                            <select id="type_cotisation" name="type_cotisation" required
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                                <option value="">Sélectionner un type</option>
                                <option value="obligatoire">Obligatoire</option>
                                <option value="volontaire">Volontaire</option>
                                <option value="evenement">Événement</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-white/80 text-sm font-medium mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                  placeholder="Description du projet"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="montant_total" class="block text-white/80 text-sm font-medium mb-2">Montant total <span class="text-red-400">*</span></label>
                            <input type="number" id="montant_total" name="montant_total" step="0.01" required
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="0.00">
                        </div>

                        <div>
                            <label for="date_debut" class="block text-white/80 text-sm font-medium mb-2">Date de début <span class="text-red-400">*</span></label>
                            <input type="date" id="date_debut" name="date_debut" required
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        </div>

                        <div>
                            <label for="date_fin" class="block text-white/80 text-sm font-medium mb-2">Date de fin <span class="text-red-400">*</span></label>
                            <input type="date" id="date_fin" name="date_fin" required
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label for="montant_par_defaut" class="block text-white/80 text-sm font-medium mb-2">Montant par défaut (optionnel)</label>
                        <input type="number" id="montant_par_defaut" name="montant_par_defaut" step="0.01" 
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                               placeholder="Montant suggéré pour tous les membres">
                        <p class="text-xs text-white/60 mt-1">Ce montant sera utilisé comme suggestion, vous pourrez le modifier individuellement pour chaque membre</p>
                    </div>

                    <div>
                        <label class="block text-white/80 text-sm font-medium mb-2">Membres à assigner avec montants <span class="text-red-400">*</span></label>
                        <div class="border border-white/20 rounded-xl p-4 max-h-64 overflow-y-auto bg-white/5">
                            <div class="space-y-3">
                                @foreach(\App\Models\Membre::where('statut', 'actif')->orderBy('nom')->get() as $membre)
                                <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg">
                                    <label class="flex items-center flex-1">
                                        <input type="checkbox" name="membres[]" value="{{ $membre->id }}" 
                                               class="rounded border-white/20 text-blue-600 focus:ring-blue-500 membre-checkbox"
                                               onchange="toggleMontantMembre({{ $membre->id }})">
                                        <span class="ml-2 text-sm text-white font-medium">{{ $membre->nom }} {{ $membre->prenom }}</span>
                                    </label>
                                    <div class="ml-4 flex items-center space-x-2">
                                        <span class="text-sm text-white/70">Montant:</span>
                                        <input type="number" 
                                               name="montants[{{ $membre->id }}]" 
                                               id="montant_{{ $membre->id }}"
                                               step="0.01" 
                                               min="0"
                                               class="w-24 px-2 py-1 text-sm border border-white/20 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white bg-white/10 membre-montant"
                                               disabled>
                                        <span class="text-sm text-white/70">FCFA</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-3 p-3 bg-blue-500/20 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-blue-300">Montant total calculé:</span>
                                <span id="montantTotalCalcule" class="text-lg font-bold text-blue-300">0 FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6">
                        <button type="button" onclick="fermerModalNouveauProjet()" 
                                class="px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200">
                            Créer le projet
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
let projets = @json($projets->items());

// Fonctions des modales
function ouvrirModalNouveauProjet() {
    document.getElementById('modalNouveauProjet').classList.remove('hidden');
}

function fermerModalNouveauProjet() {
    document.getElementById('modalNouveauProjet').classList.add('hidden');
    document.getElementById('formNouveauProjet').reset();
    // Réinitialiser les montants
    document.querySelectorAll('.membre-montant').forEach(input => {
        input.disabled = true;
        input.value = '';
    });
    document.getElementById('montantTotalCalcule').textContent = '0 FCFA';
}

// Fonction pour activer/désactiver le champ montant d'un membre
function toggleMontantMembre(membreId) {
    const checkbox = document.querySelector(`input[value="${membreId}"]`);
    const montantInput = document.getElementById(`montant_${membreId}`);
    const montantDefaut = document.getElementById('montant_par_defaut').value;
    
    if (checkbox.checked) {
        montantInput.disabled = false;
        if (montantDefaut) {
            montantInput.value = montantDefaut;
        }
    } else {
        montantInput.disabled = true;
        montantInput.value = '';
    }
    
    calculerMontantTotal();
}

// Fonction pour calculer le montant total
function calculerMontantTotal() {
    let total = 0;
    document.querySelectorAll('.membre-montant:not([disabled])').forEach(input => {
        const montant = parseFloat(input.value) || 0;
        total += montant;
    });
    
    document.getElementById('montantTotalCalcule').textContent = 
        new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
}

// Appliquer le montant par défaut à tous les membres sélectionnés
document.getElementById('montant_par_defaut').addEventListener('input', function() {
    const montantDefaut = this.value;
    document.querySelectorAll('.membre-checkbox:checked').forEach(checkbox => {
        const membreId = checkbox.value;
        const montantInput = document.getElementById(`montant_${membreId}`);
        if (!montantInput.disabled) {
            montantInput.value = montantDefaut;
        }
    });
    calculerMontantTotal();
});

// Ajouter des écouteurs pour les champs montant
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.membre-montant').forEach(input => {
        input.addEventListener('input', calculerMontantTotal);
    });
});

// Gestion du formulaire
document.getElementById('formNouveauProjet').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Collecter les membres sélectionnés avec leurs montants
    const membresSelectionnes = [];
    const montants = {};
    
    document.querySelectorAll('.membre-checkbox:checked').forEach(checkbox => {
        const membreId = checkbox.value;
        const montant = document.getElementById(`montant_${membreId}`).value;
        
        if (montant && parseFloat(montant) > 0) {
            membresSelectionnes.push(membreId);
            montants[membreId] = parseFloat(montant);
        }
    });
    
    if (membresSelectionnes.length === 0) {
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.warning('Veuillez sélectionner au moins un membre avec un montant valide');
        } else {
            alert('Veuillez sélectionner au moins un membre avec un montant valide');
        }
        return;
    }
    
    data.membres = membresSelectionnes;
    data.montants = montants;
    
    // Calculer le montant total automatiquement
    const montantTotal = Object.values(montants).reduce((sum, montant) => sum + montant, 0);
    data.montant_total = montantTotal;
    
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
            fermerModalNouveauProjet();
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
            alerteSystem.error('Erreur lors de la création du projet');
        } else {
            alert('Erreur lors de la création du projet');
        }
    });
});

       // Fonction de suppression
       function supprimerProjet(projetId, nomProjet) {
           if (typeof alerteSystem !== 'undefined' && alerteSystem) {
               alerteSystem.confirmation(`Êtes-vous sûr de vouloir supprimer le projet "${nomProjet}" ?`, function(confirmed) {
                   if (confirmed) {
                       executerSuppression(projetId);
                   }
               });
           } else {
               if (confirm(`Êtes-vous sûr de vouloir supprimer le projet "${nomProjet}" ?`)) {
                   executerSuppression(projetId);
               }
           }
       }

function executerSuppression(projetId) {
    fetch(`/cotisations/${projetId}`, {
        method: 'DELETE',
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
            alerteSystem.error('Erreur lors de la suppression');
        } else {
            alert('Erreur lors de la suppression');
        }
    });
}

// Fonctions de filtrage
function reinitialiserFiltres() {
    document.getElementById('rechercheProjet').value = '';
    document.getElementById('filtreStatut').value = '';
    document.getElementById('filtreType').value = '';
    filtrerProjets();
}

// Fonction d'export
function exporterProjets(format) {
    const url = `/cotisations/export/${format}`;
    window.open(url, '_blank');
}

function filtrerProjets() {
    const recherche = document.getElementById('rechercheProjet').value.toLowerCase();
    const statut = document.getElementById('filtreStatut').value;
    const type = document.getElementById('filtreType').value;
    
    const lignes = document.querySelectorAll('#listeProjets tr');
    
    lignes.forEach(ligne => {
        const nomProjet = ligne.querySelector('td:first-child .text-sm.font-medium')?.textContent.toLowerCase() || '';
        const statutProjet = ligne.querySelector('td:nth-child(6) span')?.textContent.toLowerCase() || '';
        const typeProjet = ligne.querySelector('td:nth-child(2) span')?.textContent.toLowerCase() || '';
        
        const correspondRecherche = nomProjet.includes(recherche);
        const correspondStatut = !statut || statutProjet.includes(statut);
        const correspondType = !type || typeProjet.includes(type);
        
        if (correspondRecherche && correspondStatut && correspondType) {
            ligne.style.display = '';
        } else {
            ligne.style.display = 'none';
        }
    });
}

// Fonction d'export
function exporterProjets() {
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.info('Fonctionnalité d\'export en cours de développement');
    } else {
        alert('Fonctionnalité d\'export en cours de développement');
    }
}

// Event listeners
document.getElementById('rechercheProjet').addEventListener('input', filtrerProjets);
document.getElementById('filtreStatut').addEventListener('change', filtrerProjets);
document.getElementById('filtreType').addEventListener('change', filtrerProjets);

// Fermer modal en cliquant à l'extérieur
document.getElementById('modalNouveauProjet').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalNouveauProjet();
    }
});
</script>
@endsection
