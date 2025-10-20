@extends('layouts.app-with-sidebar')

@section('title', 'Répétitions - ' . $activite->nom . ' - Gestion Kourel')

@section('content')
<div class="min-h-screen relative z-10">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-50 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('activites.index') }}" class="text-white/70 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-redo mr-3"></i>Répétitions - {{ $activite->nom }}
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="ouvrirModalGenerer()" 
                            class="px-4 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-magic mr-2"></i>Générer Automatiquement
                    </button>
                    <button onclick="ouvrirModalAjout()" 
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-plus mr-2"></i>Nouvelle Répétition
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24 relative z-30 ml-64">
        <!-- Messages de session -->
        @include('components.alertes-session')

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Répétitions -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Répétitions</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['total_repetitions'] }}</p>
                        <p class="text-blue-400 text-sm mt-1">Toutes périodes</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-redo text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Planifiées -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Planifiées</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['repetitions_planifiees'] }}</p>
                        <p class="text-yellow-400 text-sm mt-1">À venir</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- En Cours -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">En Cours</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['repetitions_en_cours'] }}</p>
                        <p class="text-green-400 text-sm mt-1">Actives</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-play text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Terminées -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Terminées</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['repetitions_terminees'] }}</p>
                        <p class="text-gray-400 text-sm mt-1">Passées</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check text-gray-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Répétitions -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/20">
                <h2 class="text-xl font-semibold text-white">Liste des Répétitions</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Heures</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Lieu</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Responsable</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Présences</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($repetitions as $repetition)
                        <tr class="hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-white font-medium">
                                    {{ $repetition->date_repetition->format('d/m/Y') }}
                                </div>
                                <div class="text-white/60 text-sm">
                                    {{ $repetition->date_repetition->locale('fr')->dayName }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-white">
                                    {{ \Carbon\Carbon::parse($repetition->heure_debut)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($repetition->heure_fin)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-white/80">
                                    {{ $repetition->lieu ?? 'Non spécifié' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-white/80">
                                    {{ $repetition->responsable->nom ?? 'Non assigné' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $couleurStatut = match($repetition->statut) {
                                        'planifie' => 'yellow',
                                        'confirme' => 'blue',
                                        'en_cours' => 'green',
                                        'termine' => 'gray',
                                        'annule' => 'red',
                                        default => 'gray'
                                    };
                                    $texteStatut = match($repetition->statut) {
                                        'planifie' => 'Planifiée',
                                        'confirme' => 'Confirmée',
                                        'en_cours' => 'En cours',
                                        'termine' => 'Terminée',
                                        'annule' => 'Annulée',
                                        default => 'Inconnu'
                                    };
                                @endphp
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $couleurStatut }}-500/20 text-{{ $couleurStatut }}-400 border border-{{ $couleurStatut }}-500/30">
                                    {{ $texteStatut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-white/80">
                                    {{ $repetition->presences->count() }} présences
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('repetitions.show', $repetition) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('repetitions.edit', $repetition) }}" 
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('repetitions.presences', $repetition) }}" 
                                       class="text-green-400 hover:text-green-300 transition-colors duration-200">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <button onclick="supprimerRepetition({{ $repetition->id }})" 
                                            class="text-red-400 hover:text-red-300 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p class="text-lg">Aucune répétition trouvée</p>
                                    <p class="text-sm">Commencez par créer une nouvelle répétition</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($repetitions->hasPages())
            <div class="px-6 py-4 border-t border-white/20">
                {{ $repetitions->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal Ajout de Répétition -->
<div id="modalAjout" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 w-full max-w-md">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Nouvelle Répétition</h3>
            </div>
            
            <form id="formAjout" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Date de répétition</label>
                    <input type="date" name="date_repetition" required
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Heure début</label>
                        <input type="time" name="heure_debut" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Heure fin</label>
                        <input type="time" name="heure_fin" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Lieu</label>
                    <input type="text" name="lieu" placeholder="Lieu de la répétition"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Statut</label>
                    <select name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="planifie">Planifiée</option>
                        <option value="confirme">Confirmée</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminée</option>
                        <option value="annule">Annulée</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Responsable</label>
                    <select name="responsable_id"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($membres as $membre)
                        <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Notes additionnelles"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="fermerModalAjout()"
                            class="px-4 py-2 text-white/70 hover:text-white transition-colors duration-200">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Génération Automatique -->
<div id="modalGenerer" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 w-full max-w-lg">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Générer des Répétitions Automatiquement</h3>
            </div>
            
            <form id="formGenerer" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Date début</label>
                        <input type="date" name="date_debut" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Date fin</label>
                        <input type="date" name="date_fin" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Jours de la semaine</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                        <label class="flex items-center space-x-2 text-white/80">
                            <input type="checkbox" name="jours_semaine[]" value="{{ $jour }}"
                                   class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500/50">
                            <span class="text-sm">{{ ucfirst($jour) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Heure début</label>
                        <input type="time" name="heure_debut" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Heure fin</label>
                        <input type="time" name="heure_fin" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Lieu</label>
                    <input type="text" name="lieu" placeholder="Lieu des répétitions"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Responsable</label>
                    <select name="responsable_id"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($membres as $membre)
                        <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="fermerModalGenerer()"
                            class="px-4 py-2 text-white/70 hover:text-white transition-colors duration-200">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        Générer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
const activiteId = {{ $activite->id }};

// Fonctions modals
function ouvrirModalAjout() {
    document.getElementById('modalAjout').classList.remove('hidden');
}

function fermerModalAjout() {
    document.getElementById('modalAjout').classList.add('hidden');
    document.getElementById('formAjout').reset();
}

function ouvrirModalGenerer() {
    document.getElementById('modalGenerer').classList.remove('hidden');
}

function fermerModalGenerer() {
    document.getElementById('modalGenerer').classList.add('hidden');
    document.getElementById('formGenerer').reset();
}

// Gestion du formulaire d'ajout
document.getElementById('formAjout').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/activites/${activiteId}/repetitions`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            afficherAlerte('success', data.message);
            fermerModalAjout();
            location.reload();
        } else {
            afficherAlerte('error', data.message);
        }
    } catch (error) {
        afficherAlerte('error', 'Erreur lors de la création de la répétition');
    }
});

// Gestion du formulaire de génération
document.getElementById('formGenerer').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/activites/${activiteId}/repetitions/generer`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            afficherAlerte('success', data.message);
            fermerModalGenerer();
            location.reload();
        } else {
            afficherAlerte('error', data.message);
        }
    } catch (error) {
        afficherAlerte('error', 'Erreur lors de la génération des répétitions');
    }
});

// Fonction de suppression
async function supprimerRepetition(repetitionId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette répétition ?')) {
        return;
    }
    
    try {
        const response = await fetch(`/repetitions/${repetitionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            afficherAlerte('success', data.message);
            location.reload();
        } else {
            afficherAlerte('error', data.message);
        }
    } catch (error) {
        afficherAlerte('error', 'Erreur lors de la suppression de la répétition');
    }
}

// Fermer les modals en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalAjout') {
        fermerModalAjout();
    }
    if (e.target.id === 'modalGenerer') {
        fermerModalGenerer();
    }
});
</script>
@endpush
@endsection
