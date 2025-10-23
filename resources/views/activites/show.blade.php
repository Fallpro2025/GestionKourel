@extends('layouts.app-with-sidebar')

@section('title', 'Détails de l\'Activité - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-calendar-alt mr-3"></i>{{ $activite->nom }}</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('activites.index') }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('activites.edit', $activite->id) }}" 
                       class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    @if($activite->repetitions()->count() > 0)
                    <a href="{{ route('activites.repetitions.index', $activite->id) }}" 
                       class="px-3 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                        <i class="fas fa-redo mr-2"></i>Gérer les répétitions
                    </a>
                    @endif
                    @if($activite->repetitions()->count() > 0)
                    <a href="{{ route('repetitions.presences', $activite->repetitions()->orderBy('date_repetition', 'asc')->first()) }}" 
                       class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-users mr-2"></i>Gérer les présences
                    </a>
                    @else
                    <span class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl border border-gray-500/30 cursor-not-allowed">
                        <i class="fas fa-users mr-2"></i>Gérer les présences
                    </span>
                    <span class="text-xs text-gray-400 ml-2">(Créez d'abord des répétitions)</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24 ml-0">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Informations générales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Carte principale -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Informations de l'activité</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $activite->statut === 'planifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                           ($activite->statut === 'confirme' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                           ($activite->statut === 'en_cours' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                           ($activite->statut === 'termine' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'))) }}">
                        {{ ucfirst($activite->statut) }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-white/70">Description</label>
                        <p class="text-white mt-1">{{ $activite->description ?: 'Aucune description' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Type d'activité</label>
                            <p class="text-white mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $activite->type === 'repetition' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($activite->type === 'prestation' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($activite->type === 'goudi_aldiouma' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 
                                       ($activite->type === 'formation' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $activite->type)) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Responsable</label>
                            <p class="text-white mt-1">{{ $activite->responsable ? $activite->responsable->nom . ' ' . $activite->responsable->prenom : 'Non assigné' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Date de début</label>
                            <p class="text-white mt-1">{{ $activite->date_debut->format('d/m/Y à H:i') }}</p>
                            <p class="text-white/60 text-sm mt-1">{{ $activite->date_debut->locale('fr')->dayName }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Date de fin</label>
                            <p class="text-white mt-1">{{ $activite->date_fin->format('d/m/Y à H:i') }}</p>
                            <p class="text-white/60 text-sm mt-1">{{ $activite->date_fin->locale('fr')->dayName }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-white/70">Lieu</label>
                        <p class="text-white mt-1">{{ $activite->lieu ?: 'Non spécifié' }}</p>
                    </div>

                    @if($activite->configuration)
                    <div>
                        <label class="text-sm font-medium text-white/70">Configuration</label>
                        <div class="mt-2 p-3 bg-white/5 rounded-lg">
                            <pre class="text-white text-sm">{{ json_encode($activite->configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques de présence</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Total présences</span>
                            <span class="font-medium text-white">{{ $stats['total_presences'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Présents</span>
                            <span class="font-medium text-green-400">{{ $stats['presents'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Absents justifiés</span>
                            <span class="font-medium text-yellow-400">{{ $stats['absents_justifies'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Absents non justifiés</span>
                            <span class="font-medium text-red-400">{{ $stats['absents_non_justifies'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Retards</span>
                            <span class="font-medium text-orange-400">{{ $stats['retards'] }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/20">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-white/70">Taux de présence</span>
                            <span class="font-medium text-white">{{ $stats['taux_presence'] }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-green-400 h-2 rounded-full" style="width: {{ $stats['taux_presence'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sélection et gestion des présences par répétition -->
        @if($activite->repetitions()->count() > 0)
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Gestion des présences par répétition</h3>
                    <div class="flex items-center space-x-4">
                        <label for="selectRepetition" class="text-sm font-medium text-white/70">Sélectionner une répétition :</label>
                        <select id="selectRepetition" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Choisir une répétition...</option>
                            @foreach($activite->repetitions()->orderBy('date_repetition', 'asc')->get() as $repetition)
                            <option value="{{ $repetition->id }}" class="text-gray-800">
                                {{ $repetition->date_repetition->locale('fr')->dayName }} {{ $repetition->date_repetition->format('d/m/Y') }} - {{ $repetition->heure_debut->format('H:i') }}-{{ $repetition->heure_fin->format('H:i') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="presencesContainer" class="p-6">
                <div class="text-center text-white/60">
                    <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                    <p class="text-lg">Sélectionnez une répétition</p>
                    <p class="text-sm">Choisissez une date de répétition pour voir et gérer les présences</p>
                </div>
            </div>
        </div>
        @else
        <!-- Liste des présences pour activité simple -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Liste des présences</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Heure d'arrivée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Retard</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Justification</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/5 divide-y divide-white/20">
                        @forelse($activite->presences as $presence)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($presence->membre->photo_url)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ asset('storage/' . $presence->membre->photo_url) }}" 
                                             alt="{{ $presence->membre->nom }}">
                                        @else
                                        <div class="h-10 w-10 rounded-full bg-gray-500/20 flex items-center justify-center">
                                            <i class="fas fa-user text-white/60"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</div>
                                        <div class="text-sm text-white/60">{{ $presence->membre->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $presence->statut === 'present' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($presence->statut === 'absent_justifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($presence->statut === 'absent_non_justifie' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-orange-500/20 text-orange-400 border border-orange-500/30')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $presence->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $presence->heure_arrivee ? $presence->heure_arrivee->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $presence->minutes_retard ? $presence->minutes_retard . ' min' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                {{ $presence->justification ?: '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucune présence enregistrée</p>
                                    <p class="text-sm">Les présences seront affichées ici une fois enregistrées</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Section Répétitions -->
        @if($activite->repetitions()->count() > 0)
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-redo mr-3"></i>Répétitions de l'activité
                </h2>
                <a href="{{ route('activites.repetitions.index', $activite->id) }}" 
                   class="px-4 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                    <i class="fas fa-cog mr-2"></i>Gérer toutes les répétitions
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activite->repetitions()->orderBy('date_repetition', 'asc')->limit(6)->get() as $repetition)
                <div class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-white font-semibold">
                                {{ $repetition->date_repetition->locale('fr')->dayName }}
                            </h3>
                            <p class="text-white/60 text-sm">
                                {{ $repetition->date_repetition->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $repetition->statut === 'planifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                               ($repetition->statut === 'confirme' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                               ($repetition->statut === 'en_cours' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                               ($repetition->statut === 'termine' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'))) }}">
                            {{ ucfirst($repetition->statut) }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-sm text-white/70">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-blue-400"></i>
                            {{ $repetition->heure_debut->format('H:i') }} - {{ $repetition->heure_fin->format('H:i') }}
                        </div>
                        @if($repetition->lieu)
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-green-400"></i>
                            {{ $repetition->lieu }}
                        </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2 text-purple-400"></i>
                            {{ $repetition->presences()->count() }} présences
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 mt-4">
                        <a href="{{ route('repetitions.presences', $repetition->id) }}" 
                           class="flex-1 px-3 py-2 bg-green-500/20 text-green-400 text-sm font-medium rounded-lg hover:bg-green-500/30 transition-all duration-300 border border-green-500/30 text-center">
                            <i class="fas fa-users mr-1"></i>Présences
                        </a>
                        <a href="{{ route('repetitions.show', $repetition->id) }}" 
                           class="px-3 py-2 bg-blue-500/20 text-blue-400 text-sm font-medium rounded-lg hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($activite->repetitions()->count() > 6)
            <div class="text-center mt-6">
                <a href="{{ route('activites.repetitions.index', $activite->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                    <i class="fas fa-list mr-2"></i>Voir toutes les {{ $activite->repetitions()->count() }} répétitions
                </a>
            </div>
            @endif
        </div>
        @endif
    </main>
</div>
@endsection

@section('scripts')
<script>
// Scripts spécifiques à la page de détails
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips et autres interactions
    console.log('Page de détails de l\'activité chargée');
    
    // Gestion de la sélection de répétition
    const selectRepetition = document.getElementById('selectRepetition');
    const presencesContainer = document.getElementById('presencesContainer');
    
    if (selectRepetition && presencesContainer) {
        selectRepetition.addEventListener('change', function() {
            const repetitionId = this.value;
            
            if (repetitionId) {
                // Afficher un indicateur de chargement
                presencesContainer.innerHTML = `
                    <div class="text-center text-white/60">
                        <i class="fas fa-spinner fa-spin text-4xl mb-4"></i>
                        <p class="text-lg">Chargement des présences...</p>
                    </div>
                `;
                
                // Charger les présences de la répétition sélectionnée
                chargerPresencesRepetition(repetitionId);
            } else {
                // Afficher le message par défaut
                presencesContainer.innerHTML = `
                    <div class="text-center text-white/60">
                        <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                        <p class="text-lg">Sélectionnez une répétition</p>
                        <p class="text-sm">Choisissez une date de répétition pour voir et gérer les présences</p>
                    </div>
                `;
            }
        });
    }
});

// Fonction pour charger les présences d'une répétition
function chargerPresencesRepetition(repetitionId) {
    fetch(`/repetitions/${repetitionId}/presences`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // Récupérer le HTML de la page
        } else {
            throw new Error('Erreur lors du chargement des présences');
        }
    })
    .then(html => {
        // Extraire le contenu de la section présences
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const presencesSection = doc.querySelector('.bg-white\\/10.backdrop-blur-xl.rounded-2xl.border.border-white\\/20');
        
        if (presencesSection) {
            presencesContainer.innerHTML = presencesSection.innerHTML;
        } else {
            // Si pas de section trouvée, afficher un message d'erreur
            presencesContainer.innerHTML = `
                <div class="text-center text-white/60">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p class="text-lg">Erreur de chargement</p>
                    <p class="text-sm">Impossible de charger les présences pour cette répétition</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        presencesContainer.innerHTML = `
            <div class="text-center text-white/60">
                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                <p class="text-lg">Erreur de chargement</p>
                <p class="text-sm">Impossible de charger les présences pour cette répétition</p>
            </div>
        `;
    });
}
</script>
@endsection
