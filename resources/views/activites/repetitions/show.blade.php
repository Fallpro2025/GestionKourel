@extends('layouts.app-with-sidebar')

@section('title', 'Détails de la Répétition - Gestion Kourel')

@section('content')
<div class="min-h-screen relative z-10">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-50 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-calendar-day mr-3"></i>Détails de la Répétition</h1>
                    <span class="ml-4 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        {{ $repetition->activite->nom }}
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('activites.repetitions.index', $repetition->activite->id) }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('repetitions.edit', $repetition->id) }}" 
                       class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <a href="{{ route('repetitions.presences', $repetition->id) }}" 
                       class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-users mr-2"></i>Gérer les présences
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24 relative z-30 ml-64">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Informations de la répétition -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-xl font-semibold text-white mb-6">Informations de la Répétition</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Jour</label>
                        <p class="text-white text-lg">{{ $repetition->date_repetition->locale('fr')->dayName }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Date</label>
                        <p class="text-white text-lg">{{ $repetition->date_repetition->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Heures</label>
                        <p class="text-white text-lg">{{ $repetition->heure_debut->format('H:i') }} - {{ $repetition->heure_fin->format('H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Lieu</label>
                        <p class="text-white">{{ $repetition->lieu ?: 'Non spécifié' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Responsable</label>
                        <p class="text-white">{{ $repetition->responsable ? $repetition->responsable->nom . ' ' . $repetition->responsable->prenom : 'Non assigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Statut</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $repetition->statut === 'planifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                               ($repetition->statut === 'confirme' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                               ($repetition->statut === 'en_cours' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                               ($repetition->statut === 'termine' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'))) }}">
                            {{ ucfirst($repetition->statut) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-2">Activité parente</label>
                        <a href="{{ route('activites.show', $repetition->activite->id) }}" 
                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                            {{ $repetition->activite->nom }}
                        </a>
                    </div>
                </div>
                
                @if($repetition->notes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-white/70 mb-2">Notes</label>
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <p class="text-white/80">{{ $repetition->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistiques -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-xl font-semibold text-white mb-6">Statistiques de Présence</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-white/70">Total membres</span>
                        <span class="text-white font-semibold">{{ $stats['total_membres'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-green-400">Présents</span>
                        <span class="text-green-400 font-semibold">{{ $stats['presents'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-red-400">Absents</span>
                        <span class="text-red-400 font-semibold">{{ $stats['absents'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-yellow-400">Retards</span>
                        <span class="text-yellow-400 font-semibold">{{ $stats['retards'] }}</span>
                    </div>
                    
                    <div class="border-t border-white/20 pt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-white/70">Taux de présence</span>
                            <span class="text-white font-semibold">{{ $stats['taux_presence'] }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-green-400 h-2 rounded-full" style="width: {{ $stats['taux_presence'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des présences -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Liste des Présences</h3>
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
                        @forelse($repetition->presences as $presence)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</div>
                                        <div class="text-sm text-white/60">{{ $presence->membre->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $presence->statut === 'present' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($presence->statut === 'retard' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($presence->statut === 'absent_justifie' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $presence->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                @if($presence->heure_arrivee)
                                    {{ $presence->heure_arrivee->format('H:i') }}
                                @else
                                    <span class="text-white/60">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                @if($presence->minutes_retard > 0)
                                    <span class="text-yellow-400">{{ $presence->minutes_retard }} min</span>
                                @else
                                    <span class="text-white/60">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                @if($presence->justification)
                                    <span class="text-white/80">{{ Str::limit($presence->justification, 50) }}</span>
                                @else
                                    <span class="text-white/60">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucune présence enregistrée</p>
                                    <p class="text-sm">Les présences seront affichées ici une fois marquées</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mt-6 bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
            <h3 class="text-lg font-semibold text-white mb-4">Actions Rapides</h3>
            
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('repetitions.presences', $repetition->id) }}" 
                   class="px-4 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                    <i class="fas fa-users mr-2"></i>Gérer les présences
                </a>
                
                <a href="{{ route('repetitions.edit', $repetition->id) }}" 
                   class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                    <i class="fas fa-edit mr-2"></i>Modifier la répétition
                </a>
                
                <a href="{{ route('repetitions.statistiques', $repetition->id) }}" 
                   class="px-4 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                    <i class="fas fa-chart-bar mr-2"></i>Statistiques détaillées
                </a>
                
                <button onclick="supprimerRepetition({{ $repetition->id }}, '{{ $repetition->date_repetition->format('d/m/Y') }}')"
                        class="px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
// Fonction de suppression
function supprimerRepetition(id, date) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir supprimer la répétition du ${date} ?`,
            function(confirmed) {
                if (confirmed) {
                    supprimerRepetitionConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer la répétition du ${date} ?`)) {
            supprimerRepetitionConfirm(id);
        }
    }
}

function supprimerRepetitionConfirm(id) {
    fetch(`/repetitions/${id}`, {
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
            // Rediriger vers la liste des répétitions
            window.location.href = `/activites/${data.activite_id}/repetitions`;
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
</script>
@endsection
