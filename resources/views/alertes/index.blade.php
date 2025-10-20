@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Alertes - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-bell mr-3"></i>Gestion des Alertes</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="marquerToutesLues()" 
                            class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-check-double mr-2"></i>Marquer toutes lues
                    </button>
                    <button onclick="ouvrirModalAjout()" 
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-plus mr-2"></i>Nouvelle Alerte
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Alertes -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Alertes</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['total_alertes'] }}</p>
                        <p class="text-blue-400 text-sm mt-1">Toutes périodes</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Nouvelles -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Nouvelles</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['alertes_nouvelles'] }}</p>
                        <p class="text-yellow-400 text-sm mt-1">Non traitées</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Critiques -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Critiques</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['alertes_critiques'] }}</p>
                        <p class="text-red-400 text-sm mt-1">Urgentes</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Résolues -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Résolues</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['alertes_resolues'] }}</p>
                        <p class="text-green-400 text-sm mt-1">Cette semaine</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 mb-6 border border-white/20">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="relative">
                        <input type="text" id="rechercheAlerte" placeholder="Rechercher une alerte..." 
                               class="w-full md:w-64 pl-10 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/60">
                        <i class="fas fa-search absolute left-3 top-4 text-white/60"></i>
                    </div>
                    
                    <select id="filtreType" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les types</option>
                        <option value="absence_repetitive" class="text-gray-800">Absence répétitive</option>
                        <option value="absence_non_justifiee" class="text-gray-800">Absence non justifiée</option>
                        <option value="retard_excessif" class="text-gray-800">Retard excessif</option>
                        <option value="cotisation_retard" class="text-gray-800">Cotisation en retard</option>
                        <option value="evenement_majeur" class="text-gray-800">Événement majeur</option>
                    </select>

                    <select id="filtreStatut" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les statuts</option>
                        <option value="nouveau" class="text-gray-800">Nouveau</option>
                        <option value="envoye" class="text-gray-800">Envoyé</option>
                        <option value="lu" class="text-gray-800">Lu</option>
                        <option value="resolu" class="text-gray-800">Résolu</option>
                    </select>

                    <select id="filtreUrgence" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les niveaux</option>
                        <option value="info" class="text-gray-800">Information</option>
                        <option value="warning" class="text-gray-800">Attention</option>
                        <option value="error" class="text-gray-800">Erreur</option>
                        <option value="critical" class="text-gray-800">Critique</option>
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

        <!-- Liste des alertes -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Liste des Alertes</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Alerte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Urgence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="listeAlertes" class="bg-white/5 divide-y divide-white/20">
                        @forelse($alertes as $alerte)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $alerte->getTitre() }}</div>
                                    <div class="text-sm text-white/60">{{ Str::limit($alerte->message, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alerte->type === 'absence_repetitive' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 
                                       ($alerte->type === 'absence_non_justifiee' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($alerte->type === 'retard_excessif' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($alerte->type === 'cotisation_retard' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30'))) }}">
                                    {{ $alerte->type_francais }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alerte->niveau_urgence === 'info' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($alerte->niveau_urgence === 'warning' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($alerte->niveau_urgence === 'error' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30')) }}">
                                    {{ $alerte->niveau_urgence_francais }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $alerte->membre ? $alerte->membre->nom . ' ' . $alerte->membre->prenom : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alerte->statut === 'nouveau' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($alerte->statut === 'envoye' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($alerte->statut === 'lu' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')) }}">
                                    {{ $alerte->statut_francais }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $alerte->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('alertes.show', $alerte->id) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('alertes.edit', $alerte->id) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($alerte->statut !== 'resolu')
                                    <button onclick="marquerCommeLue({{ $alerte->id }})"
                                            class="text-green-400 hover:text-green-300 transition-colors duration-200"
                                            title="Marquer comme lue">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="resoudreAlerte({{ $alerte->id }}, '{{ $alerte->getTitre() }}')"
                                            class="text-purple-400 hover:text-purple-300 transition-colors duration-200"
                                            title="Résoudre">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                    @endif
                                    <button onclick="supprimerAlerte({{ $alerte->id }}, '{{ $alerte->getTitre() }}')"
                                            class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-bell text-4xl mb-4"></i>
                                    <p class="text-lg">Aucune alerte trouvée</p>
                                    <p class="text-sm">Commencez par créer votre première alerte</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($alertes->hasPages())
            <div class="px-6 py-4 border-t border-white/20">
                {{ $alertes->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal d'ajout d'alerte -->
<div id="modalAjout" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="modalAjoutContent" class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 max-w-2xl w-full border border-white/20 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-white">Nouvelle Alerte</h3>
                <button onclick="fermerModalAjout()" class="text-white/60 hover:text-white transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="formAjoutAlerte" class="space-y-6">
                @csrf
                
                <!-- Type et Niveau d'urgence -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'alerte <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="absence_repetitive" class="text-gray-800">Absence répétitive</option>
                            <option value="absence_non_justifiee" class="text-gray-800">Absence non justifiée</option>
                            <option value="retard_excessif" class="text-gray-800">Retard excessif</option>
                            <option value="cotisation_retard" class="text-gray-800">Cotisation en retard</option>
                            <option value="evenement_majeur" class="text-gray-800">Événement majeur</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="niveau_urgence" class="block text-sm font-semibold text-white/80">Niveau d'urgence <span class="text-red-400">*</span></label>
                        <select id="niveau_urgence" name="niveau_urgence" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="info" class="text-gray-800">Information</option>
                            <option value="warning" class="text-gray-800" selected>Attention</option>
                            <option value="error" class="text-gray-800">Erreur</option>
                            <option value="critical" class="text-gray-800">Critique</option>
                        </select>
                    </div>
                </div>

                <!-- Membre et Activité/Événement -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="membre_id" class="block text-sm font-semibold text-white/80">Membre concerné</label>
                        <select id="membre_id" name="membre_id"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un membre...</option>
                            @foreach(\App\Models\Membre::where('statut', 'actif')->orderBy('nom')->get() as $membre)
                            <option value="{{ $membre->id }}" class="text-gray-800">{{ $membre->nom }} {{ $membre->prenom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="activite_id" class="block text-sm font-semibold text-white/80">Activité concernée</label>
                        <select id="activite_id" name="activite_id"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner une activité...</option>
                            @foreach(\App\Models\Activite::orderBy('date_debut', 'desc')->get() as $activite)
                            <option value="{{ $activite->id }}" class="text-gray-800">{{ $activite->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Événement -->
                <div class="space-y-2">
                    <label for="evenement_id" class="block text-sm font-semibold text-white/80">Événement concerné</label>
                    <select id="evenement_id" name="evenement_id"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Sélectionner un événement...</option>
                        @foreach(\App\Models\Evenement::orderBy('date_debut', 'desc')->get() as $evenement)
                        <option value="{{ $evenement->id }}" class="text-gray-800">{{ $evenement->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-semibold text-white/80">Message <span class="text-red-400">*</span></label>
                    <textarea id="message" name="message" rows="4" required
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Message de l'alerte"></textarea>
                </div>

                <!-- Canaux de notification -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-white/80">Canaux de notification</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="email" class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">Email</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="sms" class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">SMS</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="whatsapp" class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">WhatsApp</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="push" class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">Push</span>
                        </label>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <button type="button" onclick="fermerModalAjout()"
                            class="px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Créer l'alerte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Variables globales
let alertes = @json($alertes->items());

// Fonctions de gestion des modals
function ouvrirModalAjout() {
    const modal = document.getElementById('modalAjout');
    const modalContent = document.getElementById('modalAjoutContent');
    
    if (modal && modalContent) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
    }
}

function fermerModalAjout() {
    const modal = document.getElementById('modalAjout');
    const modalContent = document.getElementById('modalAjoutContent');
    
    if (modal && modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

// Gestion du formulaire d'ajout
document.getElementById('formAjoutAlerte').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("alertes.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            fermerModalAjout();
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la création de l\'alerte');
        } else {
            alert('Erreur lors de la création de l\'alerte');
        }
    });
});

// Fonction de marquage comme lue
function marquerCommeLue(id) {
    fetch(`/alertes/${id}/marquer-lue`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors du marquage');
        } else {
            alert('Erreur lors du marquage');
        }
    });
}

// Fonction de résolution
function resoudreAlerte(id, nom) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir résoudre l'alerte "${nom}" ?`,
            function(confirmed) {
                if (confirmed) {
                    resoudreAlerteConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir résoudre l'alerte "${nom}" ?`)) {
            resoudreAlerteConfirm(id);
        }
    }
}

function resoudreAlerteConfirm(id) {
    fetch(`/alertes/${id}/resoudre`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            resolved_by: 1 // TODO: Remplacer par l'ID de l'utilisateur connecté
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la résolution');
        } else {
            alert('Erreur lors de la résolution');
        }
    });
}

// Fonction de suppression
function supprimerAlerte(id, nom) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir supprimer l'alerte "${nom}" ?`,
            function(confirmed) {
                if (confirmed) {
                    supprimerAlerteConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'alerte "${nom}" ?`)) {
            supprimerAlerteConfirm(id);
        }
    }
}

function supprimerAlerteConfirm(id) {
    fetch(`/alertes/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la suppression');
        } else {
            alert('Erreur lors de la suppression');
        }
    });
}

// Fonction pour marquer toutes les alertes comme lues
function marquerToutesLues() {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            'Êtes-vous sûr de vouloir marquer toutes les alertes comme lues ?',
            function(confirmed) {
                if (confirmed) {
                    marquerToutesLuesConfirm();
                }
            }
        );
    } else {
        if (confirm('Êtes-vous sûr de vouloir marquer toutes les alertes comme lues ?')) {
            marquerToutesLuesConfirm();
        }
    }
}

function marquerToutesLuesConfirm() {
    fetch('/alertes/marquer-toutes-lues', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors du marquage');
        } else {
            alert('Erreur lors du marquage');
        }
    });
}

// Fonctions de filtrage
function reinitialiserFiltres() {
    document.getElementById('rechercheAlerte').value = '';
    document.getElementById('filtreType').value = '';
    document.getElementById('filtreStatut').value = '';
    document.getElementById('filtreUrgence').value = '';
    filtrerAlertes();
}

function filtrerAlertes() {
    const recherche = document.getElementById('rechercheAlerte').value.toLowerCase();
    const type = document.getElementById('filtreType').value;
    const statut = document.getElementById('filtreStatut').value;
    const urgence = document.getElementById('filtreUrgence').value;
    
    const lignes = document.querySelectorAll('#listeAlertes tr');
    
    lignes.forEach(ligne => {
        if (ligne.querySelector('td')) {
            const titre = ligne.querySelector('td').textContent.toLowerCase();
            const typeAlerte = ligne.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const urgenceAlerte = ligne.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const statutAlerte = ligne.querySelector('td:nth-child(5)').textContent.toLowerCase();
            
            const correspondRecherche = titre.includes(recherche);
            const correspondType = !type || typeAlerte.includes(type);
            const correspondStatut = !statut || statutAlerte.includes(statut);
            const correspondUrgence = !urgence || urgenceAlerte.includes(urgence);
            
            if (correspondRecherche && correspondType && correspondStatut && correspondUrgence) {
                ligne.style.display = '';
            } else {
                ligne.style.display = 'none';
            }
        }
    });
}

// Event listeners
document.getElementById('rechercheAlerte').addEventListener('input', filtrerAlertes);
document.getElementById('filtreType').addEventListener('change', filtrerAlertes);
document.getElementById('filtreStatut').addEventListener('change', filtrerAlertes);
document.getElementById('filtreUrgence').addEventListener('change', filtrerAlertes);

// Fermer le modal en cliquant à l'extérieur
document.getElementById('modalAjout').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalAjout();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fermerModalAjout();
    }
});
</script>
@endsection
