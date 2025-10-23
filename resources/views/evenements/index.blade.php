@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Événements - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-star mr-3"></i>Gestion des Événements</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="ouvrirModalAjout()" 
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-plus mr-2"></i>Nouvel Événement
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
            <!-- Total Événements -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Événements</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['total_evenements'] }}</p>
                        <p class="text-blue-400 text-sm mt-1">Toutes périodes</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Planifiés -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Planifiés</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['evenements_planifies'] }}</p>
                        <p class="text-yellow-400 text-sm mt-1">À venir</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- En cours -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">En Cours</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['evenements_en_cours'] }}</p>
                        <p class="text-green-400 text-sm mt-1">Actifs</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-play text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Budget Total -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Budget Total</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['budget_total'], 0, ',', ' ') }} FCFA</p>
                        <p class="text-purple-400 text-sm mt-1">Tous événements</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 mb-6 border border-white/20">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="relative">
                        <input type="text" id="rechercheEvenement" placeholder="Rechercher un événement..." 
                               class="w-full md:w-64 pl-10 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/60">
                        <i class="fas fa-search absolute left-3 top-4 text-white/60"></i>
                    </div>
                    
                    <select id="filtreType" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les types</option>
                        <option value="magal" class="text-gray-800">Magal</option>
                        <option value="gamou" class="text-gray-800">Gamou</option>
                        <option value="promokhane" class="text-gray-800">Promokhane</option>
                        <option value="conference" class="text-gray-800">Conférence</option>
                        <option value="formation" class="text-gray-800">Formation</option>
                        <option value="autre" class="text-gray-800">Autre</option>
                    </select>

                    <select id="filtreStatut" class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">Tous les statuts</option>
                        <option value="planifie" class="text-gray-800">Planifié</option>
                        <option value="confirme" class="text-gray-800">Confirmé</option>
                        <option value="en_cours" class="text-gray-800">En cours</option>
                        <option value="termine" class="text-gray-800">Terminé</option>
                        <option value="annule" class="text-gray-800">Annulé</option>
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

        <!-- Liste des événements -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Liste des Événements</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Événement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Lieu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Budget</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="listeEvenements" class="bg-white/5 divide-y divide-white/20">
                        @forelse($evenements as $evenement)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $evenement->nom }}</div>
                                    <div class="text-sm text-white/60">{{ Str::limit($evenement->description, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $evenement->type === 'magal' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($evenement->type === 'gamou' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($evenement->type === 'promokhane' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 
                                       ($evenement->type === 'conference' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($evenement->type === 'formation' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')))) }}">
                                    {{ $evenement->type_francais }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                <div>{{ $evenement->date_debut->format('d/m/Y') }}</div>
                                <div class="text-white/60">{{ $evenement->date_debut->format('H:i') }} - {{ $evenement->date_fin->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $evenement->lieu ?: 'Non spécifié' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $evenement->budget ? number_format($evenement->budget, 0, ',', ' ') . ' FCFA' : 'Non défini' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $evenement->statut === 'planifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($evenement->statut === 'confirme' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($evenement->statut === 'en_cours' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($evenement->statut === 'termine' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'))) }}">
                                    {{ $evenement->statut_francais }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('evenements.show', $evenement->id) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('evenements.edit', $evenement->id) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('evenements.participants', $evenement->id) }}" 
                                       class="text-green-400 hover:text-green-300 transition-colors duration-200"
                                       title="Gérer les participants">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <button onclick="supprimerEvenement({{ $evenement->id }}, '{{ $evenement->nom }}')"
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
                                    <i class="fas fa-star text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun événement trouvé</p>
                                    <p class="text-sm">Commencez par créer votre premier événement</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($evenements->hasPages())
            <div class="px-6 py-4 border-t border-white/20">
                {{ $evenements->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal d'ajout d'événement -->
<div id="modalAjout" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="modalAjoutContent" class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 max-w-2xl w-full border border-white/20 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-white">Nouvel Événement</h3>
                <button onclick="fermerModalAjout()" class="text-white/60 hover:text-white transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="formAjoutEvenement" class="space-y-6">
                @csrf
                
                <!-- Nom et Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-semibold text-white/80">Nom de l'événement <span class="text-red-400">*</span></label>
                        <input type="text" id="nom" name="nom" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Nom de l'événement">
                    </div>

                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'événement <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="magal" class="text-gray-800">Magal</option>
                            <option value="gamou" class="text-gray-800">Gamou</option>
                            <option value="promokhane" class="text-gray-800">Promokhane</option>
                            <option value="conference" class="text-gray-800">Conférence</option>
                            <option value="formation" class="text-gray-800">Formation</option>
                            <option value="autre" class="text-gray-800">Autre</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-white/80">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Description de l'événement"></textarea>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-semibold text-white/80">Date de début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-semibold text-white/80">Date de fin <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>
                </div>

                <!-- Lieu et Budget -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="lieu" class="block text-sm font-semibold text-white/80">Lieu</label>
                        <input type="text" id="lieu" name="lieu"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Lieu de l'événement">
                    </div>

                    <div class="space-y-2">
                        <label for="budget" class="block text-sm font-semibold text-white/80">Budget (FCFA)</label>
                        <input type="number" id="budget" name="budget" min="0" step="100"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Budget de l'événement">
                    </div>
                </div>

                <!-- Statut -->
                <div class="space-y-2">
                    <label for="statut" class="block text-sm font-semibold text-white/80">Statut <span class="text-red-400">*</span></label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="planifie" class="text-gray-800">Planifié</option>
                        <option value="confirme" class="text-gray-800">Confirmé</option>
                        <option value="en_cours" class="text-gray-800">En cours</option>
                        <option value="termine" class="text-gray-800">Terminé</option>
                        <option value="annule" class="text-gray-800">Annulé</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <button type="button" onclick="fermerModalAjout()"
                            class="px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Créer l'événement
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
let evenements = @json($evenements->items());

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
document.getElementById('formAjoutEvenement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("evenements.store") }}', {
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
            alerteModerne.error('Erreur lors de la création de l\'événement');
        } else {
            alert('Erreur lors de la création de l\'événement');
        }
    });
});

// Fonction de suppression
function supprimerEvenement(id, nom) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir supprimer l'événement "${nom}" ?`,
            function(confirmed) {
                if (confirmed) {
                    supprimerEvenementConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'événement "${nom}" ?`)) {
            supprimerEvenementConfirm(id);
        }
    }
}

function supprimerEvenementConfirm(id) {
    fetch(`/evenements/${id}`, {
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

// Fonctions de filtrage
function reinitialiserFiltres() {
    document.getElementById('rechercheEvenement').value = '';
    document.getElementById('filtreType').value = '';
    document.getElementById('filtreStatut').value = '';
    filtrerEvenements();
}

function filtrerEvenements() {
    const recherche = document.getElementById('rechercheEvenement').value.toLowerCase();
    const type = document.getElementById('filtreType').value;
    const statut = document.getElementById('filtreStatut').value;
    
    const lignes = document.querySelectorAll('#listeEvenements tr');
    
    lignes.forEach(ligne => {
        if (ligne.querySelector('td')) {
            const nom = ligne.querySelector('td').textContent.toLowerCase();
            const typeEvenement = ligne.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const statutEvenement = ligne.querySelector('td:nth-child(6)').textContent.toLowerCase();
            
            const correspondRecherche = nom.includes(recherche);
            const correspondType = !type || typeEvenement.includes(type);
            const correspondStatut = !statut || statutEvenement.includes(statut);
            
            if (correspondRecherche && correspondType && correspondStatut) {
                ligne.style.display = '';
            } else {
                ligne.style.display = 'none';
            }
        }
    });
}

// Event listeners
document.getElementById('rechercheEvenement').addEventListener('input', filtrerEvenements);
document.getElementById('filtreType').addEventListener('change', filtrerEvenements);
document.getElementById('filtreStatut').addEventListener('change', filtrerEvenements);

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
