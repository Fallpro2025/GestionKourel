@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Présences - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-users mr-3"></i>Gestion des Présences</h1>
                    <span class="ml-4 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        {{ $activite->nom }}
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('activites.show', $activite->id) }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <button onclick="marquerToutesPresences()" 
                            class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-check-double mr-2"></i>Marquer toutes présentes
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Informations de l'activité -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 mb-6 border border-white/20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Informations de l'activité</h3>
                    <div class="space-y-2 text-sm text-white/80">
                        <p><span class="font-medium">Type:</span> {{ ucfirst(str_replace('_', ' ', $activite->type)) }}</p>
                        <p><span class="font-medium">Date:</span> {{ $activite->date_debut->format('d/m/Y H:i') }}</p>
                        <p><span class="font-medium">Jour:</span> {{ $activite->date_debut->locale('fr')->dayName }}</p>
                        <p><span class="font-medium">Lieu:</span> {{ $activite->lieu ?: 'Non spécifié' }}</p>
                        <p><span class="font-medium">Responsable:</span> {{ $activite->responsable ? $activite->responsable->nom . ' ' . $activite->responsable->prenom : 'Non assigné' }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Statistiques</h3>
                    <div class="space-y-2 text-sm text-white/80">
                        <p><span class="font-medium">Total membres:</span> {{ $membres->count() }}</p>
                        <p><span class="font-medium">Présents:</span> <span class="text-green-400">{{ $stats['presents'] }}</span></p>
                        <p><span class="font-medium">Absents:</span> <span class="text-red-400">{{ $stats['absents'] }}</span></p>
                        <p><span class="font-medium">Retards:</span> <span class="text-yellow-400">{{ $stats['retards'] }}</span></p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Taux de présence</h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 relative">
                            <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-white/20" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                <path class="text-green-400" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $stats['taux_presence'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ $stats['taux_presence'] }}%</span>
                            </div>
                        </div>
                        <div class="text-sm text-white/80">
                            <p>{{ $stats['presents'] }} sur {{ $membres->count() }}</p>
                            <p>membres présents</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/5 divide-y divide-white/20">
                        @forelse($membres as $membre)
                        @php
                            $presence = $presences->where('membre_id', $membre->id)->first();
                        @endphp
                        <tr class="hover:bg-white/10 transition-colors duration-200" data-membre-id="{{ $membre->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $membre->nom }} {{ $membre->prenom }}</div>
                                        <div class="text-sm text-white/60">{{ $membre->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($presence)
                                    <span class="statut-presence inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $presence->statut === 'present' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                           ($presence->statut === 'retard' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                           ($presence->statut === 'absent_justifie' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $presence->statut)) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                        Non marqué
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                @if($presence && $presence->heure_arrivee)
                                    <span class="heure-arrivee">{{ $presence->heure_arrivee->setTimezone('Africa/Dakar')->format('H:i') }}</span>
                                @else
                                    <span class="heure-arrivee text-white/60">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                @if($presence && $presence->minutes_retard !== null)
                                    <span class="minutes-retard text-yellow-400">{{ $presence->minutes_retard }} min</span>
                                @else
                                    <span class="minutes-retard text-white/60">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                @if($presence && $presence->justification)
                                    <span class="justification text-white/80">{{ Str::limit($presence->justification, 30) }}</span>
                                @else
                                    <span class="justification text-white/60">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($presence)
                                    <button onclick="modifierPresence({{ $presence->id }}, {{ $membre->id }}, '{{ $membre->nom }} {{ $membre->prenom }}', '{{ $presence->statut }}', '{{ $presence->heure_arrivee ? $presence->heure_arrivee->format('H:i') : '' }}', {{ $presence->minutes_retard }}, '{{ $presence->justification }}')" 
                                            class="btn-modifier text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                            title="Modifier la présence">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif
                                    <button onclick="marquerPresence({{ $membre->id }}, 'present')" 
                                            class="text-green-400 hover:text-green-300 transition-colors duration-200"
                                            title="Marquer présent">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="marquerPresence({{ $membre->id }}, 'retard')" 
                                            class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                            title="Marquer retard">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                    <button onclick="marquerPresence({{ $membre->id }}, 'absent_justifie')" 
                                            class="text-orange-400 hover:text-orange-300 transition-colors duration-200"
                                            title="Marquer absent justifié">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button onclick="marquerPresence({{ $membre->id }}, 'absent_non_justifie')" 
                                            class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                            title="Marquer absent non justifié">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun membre trouvé</p>
                                    <p class="text-sm">Ajoutez des membres pour gérer les présences</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal de justification -->
<div id="modalJustification" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 max-w-md w-full border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Justification</h3>
                <button onclick="fermerModalJustification()" class="text-white/60 hover:text-white transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="formJustification" class="space-y-4">
                @csrf
                <input type="hidden" id="membre_id_justification" name="membre_id">
                <input type="hidden" id="statut_justification" name="statut">
                
                <div>
                    <label for="justification" class="block text-sm font-semibold text-white/80 mb-2">Justification</label>
                    <textarea id="justification" name="justification" rows="4" required
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Expliquez la raison de l'absence ou du retard..."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <button type="button" onclick="fermerModalJustification()"
                            class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification de présence -->
<div id="modalModification" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 max-w-lg w-full border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Modifier la Présence</h3>
                <button onclick="fermerModalModification()" class="text-white/60 hover:text-white transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="formModification" class="space-y-4">
                @csrf
                <input type="hidden" id="presence_id_modification" name="presence_id">
                <input type="hidden" id="membre_id_modification" name="membre_id">
                
                <!-- Affichage du membre (lecture seule) -->
                <div>
                    <label class="block text-sm font-semibold text-white/80 mb-2">Membre</label>
                    <div id="nom_membre_modification" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white/80">
                        <!-- Le nom sera rempli par JavaScript -->
                    </div>
                </div>
                
                <div>
                    <label for="statut_modification" class="block text-sm font-semibold text-white/80 mb-2">Statut</label>
                    <select id="statut_modification" name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="" class="text-gray-800">-- Sélectionner un statut --</option>
                        <option value="present" class="text-gray-800">Présent</option>
                        <option value="retard" class="text-gray-800">Retard</option>
                        <option value="absent_justifie" class="text-gray-800">Absent justifié</option>
                        <option value="absent_non_justifie" class="text-gray-800">Absent non justifié</option>
                    </select>
                </div>

                <div>
                    <label for="heure_arrivee_modification" class="block text-sm font-semibold text-white/80 mb-2">Heure d'arrivée</label>
                    <input type="time" id="heure_arrivee_modification" name="heure_arrivee"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white"
                           placeholder="Heure de début: {{ $activite->date_debut ? $activite->date_debut->format('H:i') : 'Non définie' }}"
                           min="{{ $activite->date_debut ? $activite->date_debut->format('H:i') : '' }}"
                           max="{{ $activite->date_fin ? $activite->date_fin->format('H:i') : '' }}">
                    <div id="erreur_heure_arrivee" class="text-red-400 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="minutes_retard_modification" class="block text-sm font-semibold text-white/80 mb-2">Minutes de retard</label>
                    <input type="number" id="minutes_retard_modification" name="minutes_retard" min="0" max="1440"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                           placeholder="Laisser vide pour ne pas modifier">
                </div>

                <div id="div_justification_modification">
                    <label for="justification_modification" class="block text-sm font-semibold text-white/80 mb-2">Justification</label>
                    <textarea id="justification_modification" name="justification" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Laisser vide pour ne pas modifier"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <button type="button" onclick="fermerModalModification()"
                            class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Modifier
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
let activiteId = {{ $activite->id }};
let heureDebutActivite = '{{ $activite->date_debut ? $activite->date_debut->format('H:i') : '' }}';
let heureFinActivite = '{{ $activite->date_fin ? $activite->date_fin->format('H:i') : '' }}';

// Fonction pour marquer une présence
function marquerPresence(membreId, statut) {
    if (statut === 'absent_justifie' || statut === 'retard') {
        // Ouvrir le modal de justification
        document.getElementById('membre_id_justification').value = membreId;
        document.getElementById('statut_justification').value = statut;
        document.getElementById('modalJustification').classList.remove('hidden');
    } else {
        // Marquer directement
        marquerPresenceDirect(membreId, statut);
    }
}

// Fonction pour marquer une présence directement
function marquerPresenceDirect(membreId, statut, justification = null) {
    const formData = new FormData();
    formData.append('membre_id', membreId);
    formData.append('statut', statut);
    if (justification) {
        formData.append('justification', justification);
    }

    fetch(`/activites/${activiteId}/marquer-presence`, {
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
            alerteModerne.error('Erreur lors de la mise à jour de la présence');
        } else {
            alert('Erreur lors de la mise à jour de la présence');
        }
    });
}

// Fonction pour marquer toutes les présences
function marquerToutesPresences() {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            'Êtes-vous sûr de vouloir marquer tous les membres comme présents ?',
            function(confirmed) {
                if (confirmed) {
                    marquerToutesPresencesConfirm();
                }
            }
        );
    } else {
        if (confirm('Êtes-vous sûr de vouloir marquer tous les membres comme présents ?')) {
            marquerToutesPresencesConfirm();
        }
    }
}

function marquerToutesPresencesConfirm() {
    const formData = new FormData();
    formData.append('action', 'marquer_tous_presents');

    fetch(`/activites/${activiteId}/marquer-presence`, {
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
            alerteModerne.error('Erreur lors de la mise à jour des présences');
        } else {
            alert('Erreur lors de la mise à jour des présences');
        }
    });
}

// Gestion du formulaire de justification
document.getElementById('formJustification').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const membreId = document.getElementById('membre_id_justification').value;
    const statut = document.getElementById('statut_justification').value;
    const justification = document.getElementById('justification').value;
    
    marquerPresenceDirect(membreId, statut, justification);
    fermerModalJustification();
});

// Fonctions de gestion du modal
function fermerModalJustification() {
    document.getElementById('modalJustification').classList.add('hidden');
    document.getElementById('justification').value = '';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('modalJustification').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalJustification();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fermerModalJustification();
        fermerModalModification();
    }
});

// Fonction pour ouvrir le modal de modification
function modifierPresence(presenceId, membreId, nomMembre, statut, heureArrivee, minutesRetard, justification) {
    console.log('=== MODIFICATION PRÉSENCE ===');
    console.log('Presence ID:', presenceId);
    console.log('Membre ID:', membreId);
    console.log('Nom membre:', nomMembre);
    console.log('Statut actuel:', statut);
    console.log('Heure arrivée:', heureArrivee);
    console.log('Minutes retard:', minutesRetard);
    console.log('Justification:', justification);

    // Remplir le formulaire
    document.getElementById('presence_id_modification').value = presenceId;
    document.getElementById('membre_id_modification').value = membreId;
    
    // Afficher le nom du membre (lecture seule)
    document.getElementById('nom_membre_modification').textContent = nomMembre;
    
    // Pré-remplir avec les valeurs actuelles
    document.getElementById('statut_modification').value = statut || '';
    document.getElementById('heure_arrivee_modification').value = heureArrivee || '';
    document.getElementById('minutes_retard_modification').value = minutesRetard || '';
    document.getElementById('justification_modification').value = justification || '';

    // Afficher le modal
    document.getElementById('modalModification').classList.remove('hidden');
}

// Fonction pour fermer le modal de modification
function fermerModalModification() {
    document.getElementById('modalModification').classList.add('hidden');
    document.getElementById('formModification').reset();
}

// Gestion du formulaire de modification
document.getElementById('formModification').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const presenceId = document.getElementById('presence_id_modification').value;
    const statut = document.getElementById('statut_modification').value;
    const heureArrivee = document.getElementById('heure_arrivee_modification').value;
    const minutesRetard = document.getElementById('minutes_retard_modification').value;
    const justification = document.getElementById('justification_modification').value;
    
    console.log('=== SOUMISSION MODIFICATION ===');
    console.log('Presence ID:', presenceId);
    console.log('Statut:', statut);
    console.log('Heure arrivée:', heureArrivee);
    console.log('Minutes retard:', minutesRetard);
    console.log('Justification:', justification);
    
    // Valider l'heure d'arrivée avant soumission
    if (heureArrivee && !validerHeureArrivee(heureArrivee)) {
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Veuillez corriger l\'heure d\'arrivée avant de continuer');
        } else {
            alert('Veuillez corriger l\'heure d\'arrivée avant de continuer');
        }
        return;
    }
    
    // Préparer les données
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT');
    
    // Ajouter seulement les champs non vides
    if (statut) {
        formData.append('statut', statut);
    }
    if (heureArrivee) {
        formData.append('heure_arrivee', heureArrivee);
    }
    if (minutesRetard) {
        formData.append('minutes_retard', minutesRetard);
    }
    if (justification) {
        formData.append('justification', justification);
    }
    
    // Envoyer la requête
    fetch(`/presences/${presenceId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('=== RÉPONSE MODIFICATION ===');
        console.log('Data:', data);
        
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            fermerModalModification();
            // Mettre à jour la ligne de présence dans le tableau
            actualiserLignePresence(data.presence);
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
            // Ne pas fermer le modal en cas d'erreur pour permettre de corriger
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la modification de la présence');
        } else {
            alert('Erreur lors de la modification de la présence');
        }
    });
});

// Fonction pour actualiser une ligne de présence dans le tableau
function actualiserLignePresence(presence) {
    console.log('=== ACTUALISATION LIGNE PRÉSENCE ===');
    console.log('Presence:', presence);
    
    // Trouver la ligne correspondante dans le tableau
    const membreId = presence.membre_id;
    const lignePresence = document.querySelector(`tr[data-membre-id="${membreId}"]`);
    
    if (!lignePresence) {
        console.warn('Ligne de présence non trouvée pour le membre ID:', membreId);
        return;
    }
    
    // Mettre à jour le statut
    const celluleStatut = lignePresence.querySelector('.statut-presence');
    if (celluleStatut) {
        const statutFrancais = {
            'present': 'Présent',
            'retard': 'Retard',
            'absent_justifie': 'Absent justifié',
            'absent_non_justifie': 'Absent non justifié'
        };
        
        celluleStatut.textContent = statutFrancais[presence.statut] || presence.statut;
        
        // Mettre à jour la classe CSS selon le statut
        celluleStatut.className = 'statut-presence px-4 py-2 rounded-full text-sm font-medium ';
        switch(presence.statut) {
            case 'present':
                celluleStatut.className += 'bg-green-500/20 text-green-400 border border-green-500/30';
                break;
            case 'retard':
                celluleStatut.className += 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30';
                break;
            case 'absent_justifie':
                celluleStatut.className += 'bg-blue-500/20 text-blue-400 border border-blue-500/30';
                break;
            case 'absent_non_justifie':
                celluleStatut.className += 'bg-red-500/20 text-red-400 border border-red-500/30';
                break;
        }
    }
    
    // Mettre à jour l'heure d'arrivée
    const celluleHeure = lignePresence.querySelector('.heure-arrivee');
    if (celluleHeure && presence.heure_arrivee) {
        const heure = new Date(presence.heure_arrivee).toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        celluleHeure.textContent = heure;
    }
    
    // Mettre à jour les minutes de retard
    const celluleRetard = lignePresence.querySelector('.minutes-retard');
    if (celluleRetard) {
        celluleRetard.textContent = presence.minutes_retard || '0';
    }
    
    // Mettre à jour la justification
    const celluleJustification = lignePresence.querySelector('.justification');
    if (celluleJustification) {
        celluleJustification.textContent = presence.justification || '-';
    }
    
    // Mettre à jour le bouton de modification avec les nouvelles données
    const boutonModifier = lignePresence.querySelector('.btn-modifier');
    if (boutonModifier) {
        const nomMembre = presence.membre ? `${presence.membre.nom} ${presence.membre.prenom}` : 'Membre inconnu';
        const heureArrivee = presence.heure_arrivee ? new Date(presence.heure_arrivee).toTimeString().slice(0, 5) : '';
        
        boutonModifier.setAttribute('onclick', 
            `modifierPresence(${presence.id}, ${presence.membre_id}, '${nomMembre}', '${presence.statut}', '${heureArrivee}', ${presence.minutes_retard || 0}, '${presence.justification || ''}')`
        );
    }
    
    console.log('Ligne de présence mise à jour avec succès');
}

// Fermer le modal de modification en cliquant à l'extérieur
document.getElementById('modalModification').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalModification();
    }
});

// Gestion de l'affichage de la justification selon le statut
document.getElementById('statut_modification').addEventListener('change', function() {
    const statut = this.value;
    const divJustification = document.getElementById('div_justification_modification');
    
    console.log('=== CHANGEMENT STATUT ===');
    console.log('Statut sélectionné:', statut);
    
    if (statut === 'present') {
        // Masquer la justification pour "Présent"
        divJustification.style.display = 'none';
        document.getElementById('justification_modification').value = '';
        console.log('Justification masquée pour statut "Présent"');
    } else {
        // Afficher la justification pour les autres statuts
        divJustification.style.display = 'block';
        console.log('Justification affichée pour statut:', statut);
    }
});

// Validation de l'heure d'arrivée
function validerHeureArrivee(heureArrivee) {
    const erreurDiv = document.getElementById('erreur_heure_arrivee');
    const inputHeure = document.getElementById('heure_arrivee_modification');
    
    console.log('=== VALIDATION HEURE ARRIVÉE ===');
    console.log('Heure arrivée:', heureArrivee);
    console.log('Heure début activité:', heureDebutActivite);
    console.log('Heure fin activité:', heureFinActivite);
    
    // Vérifier si l'heure est fournie
    if (!heureArrivee) {
        erreurDiv.classList.add('hidden');
        inputHeure.classList.remove('border-red-500');
        inputHeure.classList.add('border-white/20');
        return true;
    }
    
    // Vérifier si l'heure de début est définie
    if (!heureDebutActivite) {
        erreurDiv.classList.add('hidden');
        inputHeure.classList.remove('border-red-500');
        inputHeure.classList.add('border-white/20');
        return true;
    }
    
    // Convertir les heures en minutes pour comparaison
    const [hArrivee, mArrivee] = heureArrivee.split(':').map(Number);
    const [hDebut, mDebut] = heureDebutActivite.split(':').map(Number);
    const minutesArrivee = hArrivee * 60 + mArrivee;
    const minutesDebut = hDebut * 60 + mDebut;
    
    // Vérifier si l'heure d'arrivée est antérieure à l'heure de début
    if (minutesArrivee < minutesDebut) {
        erreurDiv.textContent = `L'heure d'arrivée ne peut pas être antérieure à l'heure de début (${heureDebutActivite})`;
        erreurDiv.classList.remove('hidden');
        inputHeure.classList.remove('border-white/20');
        inputHeure.classList.add('border-red-500');
        return false;
    }
    
    // Vérifier si l'heure de fin est définie
    if (heureFinActivite) {
        const [hFin, mFin] = heureFinActivite.split(':').map(Number);
        const minutesFin = hFin * 60 + mFin;
        
        // Vérifier si l'heure d'arrivée est postérieure à l'heure de fin
        if (minutesArrivee > minutesFin) {
            erreurDiv.textContent = `L'heure d'arrivée ne peut pas être postérieure à l'heure de fin (${heureFinActivite})`;
            erreurDiv.classList.remove('hidden');
            inputHeure.classList.remove('border-white/20');
            inputHeure.classList.add('border-red-500');
            return false;
        }
    }
    
    // Heure valide
    erreurDiv.classList.add('hidden');
    inputHeure.classList.remove('border-red-500');
    inputHeure.classList.add('border-white/20');
    return true;
}

// Événement de validation en temps réel
document.getElementById('heure_arrivee_modification').addEventListener('input', function() {
    validerHeureArrivee(this.value);
});

// Événement de validation au blur
document.getElementById('heure_arrivee_modification').addEventListener('blur', function() {
    validerHeureArrivee(this.value);
});
</script>
@endsection
