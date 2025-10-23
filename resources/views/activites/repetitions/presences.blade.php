@extends('layouts.app-with-sidebar')

@section('title', 'Présences - ' . $repetition->activite->nom . ' - Gestion Kourel')

@section('content')
<div class="min-h-screen relative z-10">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-50 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('activites.repetitions.index', $repetition->activite) }}" class="text-white/70 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-users mr-3"></i>Présences - {{ $repetition->activite->nom }}
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="ouvrirModalAjout()" 
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-plus mr-2"></i>Marquer Présence
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24 relative z-30 ml-64">
        <!-- Messages de session -->
        @include('components.alertes-session')

        <!-- Informations de la répétition -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Informations de la répétition</h3>
                    <div class="space-y-2 text-white/80">
                        <p><strong>Jour:</strong> {{ $repetition->date_repetition->locale('fr')->dayName }}</p>
                        <p><strong>Date:</strong> {{ $repetition->date_repetition->format('d/m/Y') }}</p>
                        <p><strong>Heures:</strong> {{ \Carbon\Carbon::parse($repetition->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($repetition->heure_fin)->format('H:i') }}</p>
                        <p><strong>Lieu:</strong> {{ $repetition->lieu ?? 'Non spécifié' }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Statut</h3>
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
                    <span class="px-4 py-2 text-sm font-medium rounded-full bg-{{ $couleurStatut }}-500/20 text-{{ $couleurStatut }}-400 border border-{{ $couleurStatut }}-500/30">
                        {{ $texteStatut }}
                    </span>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Responsable</h3>
                    <p class="text-white/80">{{ $repetition->responsable->nom ?? 'Non assigné' }}</p>
                </div>
            </div>
        </div>

        <!-- Statistiques de présence -->
        @php
            $stats = $repetition->getStatistiquesPresence();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Présences -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Présences</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['total_presences'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Présents -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Présents</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['presents'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Absents -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Absents</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['absents_justifies'] + $stats['absents_non_justifies'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-times text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Retards -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Retards</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ $stats['retards'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestion des Présences pour tous les membres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/20">
                <h2 class="text-xl font-semibold text-white">Gestion des Présences</h2>
                <p class="text-white/60 text-sm mt-1">Marquez la présence de chaque membre pour cette répétition</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($membres as $membre)
                        @php
                            $presence = $presences->where('membre_id', $membre->id)->first();
                            $statutCourant = $presence ? $presence->statut : null;
                        @endphp
                        <tr class="hover:bg-white/5 transition-colors duration-200" data-membre-id="{{ $membre->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-4">
                                        {{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">
                                            {{ $membre->nom }} {{ $membre->prenom }}
                                        </div>
                                        <div class="text-white/60 text-sm">
                                            {{ $membre->matricule }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($statutCourant)
                                    @php
                                        $couleurStatut = match($statutCourant) {
                                            'present' => 'green',
                                            'absent_justifie' => 'yellow',
                                            'absent_non_justifie' => 'red',
                                            'retard' => 'orange',
                                            default => 'gray'
                                        };
                                        $texteStatut = match($statutCourant) {
                                            'present' => 'Présent',
                                            'absent_justifie' => 'Absent (justifié)',
                                            'absent_non_justifie' => 'Absent (non justifié)',
                                            'retard' => 'En retard',
                                            default => 'Inconnu'
                                        };
                                    @endphp
                                    <div class="space-y-1">
                                        <span class="statut-presence px-3 py-1 text-xs font-medium rounded-full bg-{{ $couleurStatut }}-500/20 text-{{ $couleurStatut }}-400 border border-{{ $couleurStatut }}-500/30">
                                        {{ $texteStatut }}
                                    </span>
                                        
                                        @if($presence && $presence->heure_arrivee)
                                            <div class="text-xs text-white/60">
                                                🕐 {{ $presence->heure_arrivee->setTimezone('Africa/Dakar')->format('H:i') }}
                                                @if($presence->minutes_retard > 0)
                                                    <span class="text-orange-400">({{ $presence->minutes_retard }} min)</span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($presence && $presence->minutes_retard > 0 && $statutCourant === 'retard')
                                            <div class="text-xs text-orange-400">
                                                ⏳ {{ $presence->minutes_retard }} min de retard
                                            </div>
                                        @endif
                                        
                                        
                                        @if($presence && $presence->justification)
                                            <div class="text-xs text-white/70 max-w-xs truncate" title="{{ $presence->justification }}">
                                                <i class="fas fa-comment mr-1"></i>{{ Str::limit($presence->justification, 30) }}
                                            </div>
                                        @endif
                                        
                                        @if($presence && $presence->prestation_effectuee)
                                            <div class="text-xs text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>Prestation effectuée
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                        Non marqué
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @if(!$statutCourant || $statutCourant !== 'present')
                                    <button data-member-id="{{ $membre->id }}" data-status="present" class="btn-marquer-presente px-3 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-lg hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                                        <i class="fas fa-check mr-1"></i>Présent
                                    </button>
                                    @endif
                                    @if(!$statutCourant || $statutCourant !== 'absent_justifie')
                                    <button data-member-id="{{ $membre->id }}" data-status="absent_justifie" class="btn-marquer-absent-justifie px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-lg hover:bg-yellow-500/30 transition-all duration-300 border border-yellow-500/30">
                                        <i class="fas fa-clock mr-1"></i>Absent justifié
                                    </button>
                                    @endif
                                    @if(!$statutCourant || $statutCourant !== 'absent_non_justifie')
                                    <button data-member-id="{{ $membre->id }}" data-status="absent_non_justifie" class="btn-marquer-absent-non-justifie px-3 py-1 bg-red-500/20 text-red-400 text-xs font-medium rounded-lg hover:bg-red-500/30 transition-all duration-300 border border-red-500/30">
                                        <i class="fas fa-times mr-1"></i>Absent non justifié
                                    </button>
                                    @endif
                                    @if(!$statutCourant || $statutCourant !== 'retard')
                                    <button data-member-id="{{ $membre->id }}" data-status="retard" class="btn-marquer-retard px-3 py-1 bg-orange-500/20 text-orange-400 text-xs font-medium rounded-lg hover:bg-orange-500/30 transition-all duration-300 border border-orange-500/30">
                                        <i class="fas fa-hourglass-half mr-1"></i>Retard
                                    </button>
                                    @endif
                                    @if($statutCourant)
                                    <button data-member-id="{{ $membre->id }}" 
                                            data-statut-actuel="{{ $statutCourant }}" 
                                            data-nom-membre="{{ $membre->nom }} {{ $membre->prenom }}" 
                                            data-heure-arrivee="{{ $presence && $presence->heure_arrivee ? $presence->heure_arrivee->format('H:i') : '' }}"
                                            data-minutes-retard="{{ $presence ? $presence->minutes_retard : 0 }}"
                                            data-justification="{{ $presence ? $presence->justification : '' }}"
                                            data-prestation-effectuee="{{ $presence ? ($presence->prestation_effectuee ? 'true' : 'false') : 'false' }}"
                                            data-notes-prestation="{{ $presence ? $presence->notes_prestation : '' }}"
                                            class="btn-modifier-presence px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-users-slash text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun membre trouvé</p>
                                    <p class="text-sm">Assurez-vous qu'il y a des membres actifs dans le système</p>
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

<!-- Modal Ajout de Présence -->
<div id="modalAjout" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 w-full max-w-md max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b border-white/20 flex-shrink-0">
                <h3 class="text-lg font-semibold text-white">Marquer une Présence</h3>
            </div>
            
            <div class="flex-1 overflow-y-auto">
            <form id="formAjout" class="p-6 space-y-4">
                @csrf
                <!-- Champ caché pour l'ID du membre en mode modification -->
                <input type="hidden" name="membre_id_modification" id="membre_id_modification" value="">
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Membre</label>
                    <div id="membre-info" class="mb-2 hidden">
                        <p class="text-blue-400 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Membre pré-sélectionné pour cette action
                        </p>
                    </div>
                    <!-- Affichage du membre en lecture seule lors de la modification -->
                    <div id="nom_membre_modification" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white/80 hidden">
                        <!-- Le nom sera rempli par JavaScript -->
                    </div>
                    <!-- Select pour création de nouvelle présence -->
                    <select name="membre_id" id="membre_id" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">Sélectionner un membre</option>
                        @foreach($membres as $membre)
                        <option value="{{ $membre->id }}">{{ $membre->nom }} {{ $membre->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Statut</label>
                    <select name="statut" id="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                            onchange="gererAffichageChamps(this.value)">
                        <option value="">Sélectionner un statut</option>
                        <option value="present">Présent</option>
                        <option value="absent_justifie">Absent (justifié)</option>
                        <option value="absent_non_justifie">Absent (non justifié)</option>
                        <option value="retard">En retard</option>
                    </select>
                </div>
                
                <div id="heureField" class="hidden">
                    <label class="block text-sm font-medium text-white/80 mb-2">Heure d'arrivée</label>
                    <input type="time" name="heure_arrivee" id="heure_arrivee"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                           onchange="calculerMinutesRetard()">
                    <p class="text-xs text-blue-300 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Les minutes de retard seront calculées automatiquement
                    </p>
                </div>
                
                <div id="retardField" class="hidden">
                    <label class="block text-sm font-medium text-white/80 mb-2">Minutes de retard</label>
                    <input type="number" name="minutes_retard" id="minutes_retard" min="0" placeholder="0" readonly
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white/70 placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <p class="text-xs text-blue-300 mt-1">
                        <i class="fas fa-calculator mr-1"></i>
                        Calculé automatiquement selon l'heure d'arrivée
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Justification</label>
                    <textarea name="justification" rows="3" placeholder="Justification (optionnel)"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50"></textarea>
                </div>
                
                <div>
                    <label class="flex items-center space-x-2 text-white/80">
                        <input type="checkbox" name="prestation_effectuee" value="1"
                               class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500/50">
                        <span class="text-sm">Prestation effectuée</span>
                    </label>
                </div>
                
                <div id="prestationField" class="hidden">
                    <label class="block text-sm font-medium text-white/80 mb-2">Notes sur la prestation</label>
                    <textarea name="notes_prestation" rows="2" placeholder="Notes sur la prestation"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50"></textarea>
                </div>
                
                </form>
            </div>
            
            <!-- Boutons d'action fixes en bas -->
            <div class="px-6 py-4 border-t border-white/20 flex-shrink-0">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="fermerModalAjout()"
                            class="px-4 py-2 text-white/70 hover:text-white transition-colors duration-200">
                        Annuler
                    </button>
                    <button type="submit" form="formAjout"
                            class="px-6 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
const repetitionId = {{ $repetition->id ?? 0 }};

// Fonctions modals
function ouvrirModalAjout() {
    document.getElementById('modalAjout').classList.remove('hidden');
    // Appeler la fonction pour gérer l'affichage des champs selon le statut sélectionné
    const statutSelect = document.getElementById('statut');
    if (statutSelect && statutSelect.value) {
        gererAffichageChamps(statutSelect.value);
    }
}

function fermerModalAjout() {
    document.getElementById('modalAjout').classList.add('hidden');
    desactiverModeModification();
    resetModalAjout();
}

function resetModalAjout() {
    const membreSelect = document.getElementById('membre_id');
    const statutSelect = document.getElementById('statut');
    
    if (membreSelect) {
        membreSelect.disabled = false;
        membreSelect.style.opacity = '1';
        membreSelect.value = '';
        Array.from(membreSelect.options).forEach(option => {
            option.style.display = 'block';
        });
    }
    
    const membreInfo = document.getElementById('membre-info');
    if (membreInfo) {
        membreInfo.classList.add('hidden');
    }
    
    if (statutSelect) {
        statutSelect.value = '';
    }
    
    document.getElementById('formAjout').reset();
    document.getElementById('retardField').classList.add('hidden');
    document.getElementById('prestationField').classList.add('hidden');
}

// Fonction pour marquer la présence d'un membre
async function marquerPresence(membreId, statut) {
    console.log('=== MARQUER PRÉSENCE ===');
    console.log('Membre ID:', membreId);
    console.log('Statut:', statut);
    
    membreId = parseInt(membreId);
    
    // Si le statut nécessite une justification, ouvrir le modal
    if (statut === 'absent_justifie' || statut === 'retard') {
        ouvrirModalAjout();
        document.getElementById('membre_id').value = membreId;
        document.getElementById('statut').value = statut;
        
        // Appeler la fonction pour afficher les champs selon le statut
        gererAffichageChamps(statut);
        
        const membreSelect = document.getElementById('membre_id');
        const membreInfo = document.getElementById('membre-info');
        
        membreSelect.disabled = true;
        membreSelect.style.opacity = '0.6';
        membreInfo.classList.remove('hidden');
        
        Array.from(membreSelect.options).forEach(option => {
            if (option.value !== membreId.toString() && option.value !== '') {
                option.style.display = 'none';
            }
        });
        
        return;
    }
    
    // Obtenir le nom du membre
    const membreSelect = document.getElementById('membre_id');
    const membreOption = membreSelect.querySelector(`option[value="${membreId}"]`);
    const nomMembre = membreOption ? membreOption.textContent : 'ce membre';
    
    // Fonction pour exécuter l'action
    function executerAction() {
        console.log('=== EXÉCUTION ACTION ===');
        console.log('Membre ID:', membreId);
        console.log('Statut:', statut);
        
        const formData = new FormData();
        formData.append('membre_id', membreId);
        formData.append('statut', statut);
        formData.append('prestation_effectuee', 'false');
        
        console.log('FormData créé:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }
        
        const url = `/repetitions/${repetitionId}/marquer-presence`;
        console.log('URL:', url);
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('=== RÉPONSE SERVEUR ===');
            console.log('Status:', response.status);
            console.log('Status Text:', response.statusText);
            return response.json();
        })
        .then(data => {
            console.log('=== DONNÉES RÉPONSE ===');
            console.log('Data:', data);
            
            if (data.success) {
                console.log('✅ Succès:', data.message);
                if (typeof alerteModerne !== 'undefined' && alerteModerne.success) {
                    alerteModerne.success(data.message);
                } else {
                    alert(data.message);
                }
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                console.error('❌ Erreur:', data.message);
                console.error('Erreurs détaillées:', data.errors);
                
                let errorMessage = data.message;
                if (data.errors && Object.keys(data.errors).length > 0) {
                    errorMessage += '\n\nDétails des erreurs:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
                    });
                }
                
                if (typeof alerteModerne !== 'undefined' && alerteModerne.error) {
                    alerteModerne.error(data.message);
                } else {
                    alert(`Erreur: ${errorMessage}`);
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof alerteModerne !== 'undefined' && alerteModerne.error) {
                alerteModerne.error('Erreur lors de l\'enregistrement de la présence');
            } else {
                alert('Erreur lors de l\'enregistrement de la présence');
            }
        });
    }
    
    // Validation pour les présents
    if (statut === 'present') {
        if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
            alerteModerne.confirmation(
                `Marquer ${nomMembre} comme présent ?`,
                function(confirmed) {
                    if (confirmed) {
                        executerAction();
                    }
                }
            );
        } else {
            if (confirm(`Marquer ${nomMembre} comme présent ?`)) {
                executerAction();
            }
        }
        return;
    }
    
    // Validation pour les absents non justifiés
    if (statut === 'absent_non_justifie') {
        if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
            alerteModerne.confirmation(
                `Marquer ${nomMembre} comme absent (non justifié) ?`,
                function(confirmed) {
                    if (confirmed) {
                        executerAction();
                    }
                }
            );
        } else {
            if (confirm(`Marquer ${nomMembre} comme absent (non justifié) ?`)) {
                executerAction();
            }
        }
        return;
    }
}

// Fonction pour modifier une présence de répétition
function modifierPresenceRepetition(membreId, nomMembre, statut, heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation) {
    console.log('=== MODIFICATION PRÉSENCE RÉPÉTITION ===');
    console.log('Membre ID:', membreId);
    console.log('Nom membre:', nomMembre);
    console.log('Statut actuel:', statut);
    console.log('Heure arrivée:', heureArrivee);
    console.log('Minutes retard:', minutesRetard);
    console.log('Justification:', justification);
    console.log('Prestation effectuée:', prestationEffectuee);
    console.log('Notes prestation:', notesPrestation);
    
    ouvrirModalAjout();
    
    // Activer le mode modification avec toutes les données
    activerModeModification(membreId, nomMembre, statut, heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation);
}

// Fonction pour activer le mode modification
function activerModeModification(membreId, nomMembre, statut, heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation) {
    // Masquer le select et afficher le nom du membre
    document.getElementById('membre_id').classList.add('hidden');
    document.getElementById('nom_membre_modification').classList.remove('hidden');
    document.getElementById('nom_membre_modification').textContent = nomMembre;
    
    // Pré-remplir les champs
    document.getElementById('membre_id').value = membreId;
    document.getElementById('statut').value = statut || '';
    
    // Pré-remplir l'heure d'arrivée
    if (heureArrivee) {
        document.getElementById('heure_arrivee').value = heureArrivee;
    }
    
    // Pré-remplir les minutes de retard
    if (minutesRetard) {
        document.getElementById('minutes_retard').value = minutesRetard;
    }
    
    // Pré-remplir la justification
    if (justification) {
        document.querySelector('textarea[name="justification"]').value = justification;
    }
    
    // Pré-remplir la prestation effectuée
    const checkboxPrestation = document.querySelector('input[name="prestation_effectuee"]');
    if (checkboxPrestation) {
        checkboxPrestation.checked = prestationEffectuee === 'true' || prestationEffectuee === true || prestationEffectuee === '1';
    }
    
    // Pré-remplir les notes de prestation
    if (notesPrestation) {
        document.querySelector('textarea[name="notes_prestation"]').value = notesPrestation;
    }
    
    // Afficher/masquer les champs selon le statut
    gererAffichageChamps(statut);
    
    // Changer le titre du modal
    document.querySelector('#modalAjout h3').textContent = 'Modifier la Présence';
    
    // Ajouter un champ caché pour identifier le mode modification
    let inputHidden = document.getElementById('mode_modification');
    if (!inputHidden) {
        inputHidden = document.createElement('input');
        inputHidden.type = 'hidden';
        inputHidden.id = 'mode_modification';
        inputHidden.name = 'mode_modification';
        inputHidden.value = 'true';
        document.getElementById('formAjout').appendChild(inputHidden);
    }
}

// Fonction globale pour gérer l'affichage des champs selon le statut
function gererAffichageChamps(statut) {
    console.log('=== GESTION AFFICHAGE CHAMPS ===');
    console.log('Statut sélectionné:', statut);
    
    const heureField = document.getElementById('heureField');
    const retardField = document.getElementById('retardField');
    const prestationField = document.getElementById('prestationField');
    
    console.log('Champs trouvés:', {
        heureField: !!heureField,
        retardField: !!retardField,
        prestationField: !!prestationField
    });
    
    // Masquer tous les champs conditionnels
    if (heureField) heureField.classList.add('hidden');
    if (retardField) retardField.classList.add('hidden');
    if (prestationField) prestationField.classList.add('hidden');
    
    // Afficher les champs selon le statut
    switch(statut) {
        case 'present':
            console.log('Affichage champs pour: Présent');
            if (heureField) heureField.classList.remove('hidden');
            if (prestationField) prestationField.classList.remove('hidden');
            break;
        case 'retard':
            console.log('Affichage champs pour: Retard');
            if (heureField) heureField.classList.remove('hidden');
            if (retardField) {
                retardField.classList.remove('hidden');
                console.log('Champ retard affiché');
            } else {
                console.log('Champ retard non trouvé');
            }
            if (prestationField) prestationField.classList.remove('hidden');
            break;
        case 'absent_justifie':
            console.log('Affichage champs pour: Absent justifié');
            // Pas de champs supplémentaires pour les absents justifiés
            break;
        case 'absent_non_justifie':
            console.log('Affichage champs pour: Absent non justifié');
            // Pas de champs supplémentaires pour les absents non justifiés
            break;
        default:
            console.log('Statut non reconnu:', statut);
    }
    
    console.log('=== FIN GESTION AFFICHAGE CHAMPS ===');
}

// Fonction pour calculer automatiquement les minutes de retard
function calculerMinutesRetard() {
    console.log('=== CALCUL MINUTES RETARD ===');
    
    const heureArrivee = document.getElementById('heure_arrivee').value;
    const minutesRetardInput = document.getElementById('minutes_retard');
    
    console.log('Heure d\'arrivée saisie:', heureArrivee);
    
    if (!heureArrivee) {
        console.log('Pas d\'heure d\'arrivée, pas de calcul');
        return;
    }
    
    // Récupérer l'heure de début de l'ACTIVITÉ (pas de la répétition)
    const heureDebutActivite = '{{ $repetition->activite->date_debut ? $repetition->activite->date_debut->format("H:i") : "" }}';
    console.log('Heure de début de l\'activité:', heureDebutActivite);
    
    if (!heureDebutActivite) {
        console.log('Pas d\'heure de début définie pour l\'activité');
        return;
    }
    
    try {
        // Créer les objets Date pour la comparaison
        const [heureDebut, minuteDebut] = heureDebutActivite.split(':');
        const [heureArriveeH, minuteArriveeM] = heureArrivee.split(':');
        
        console.log('Heure début activité:', heureDebut, 'Minute début:', minuteDebut);
        console.log('Heure arrivée:', heureArriveeH, 'Minute arrivée:', minuteArriveeM);
        
        // Convertir en minutes depuis minuit pour faciliter le calcul
        const minutesDebut = parseInt(heureDebut) * 60 + parseInt(minuteDebut);
        const minutesArrivee = parseInt(heureArriveeH) * 60 + parseInt(minuteArriveeM);
        
        // Calculer la différence
        const differenceMinutes = minutesArrivee - minutesDebut;
        
        console.log('Minutes de début activité:', minutesDebut);
        console.log('Minutes d\'arrivée:', minutesArrivee);
        console.log('Différence calculée:', differenceMinutes);
        console.log('FORMULE: heure_arrivée - heure_début =', minutesArrivee, '-', minutesDebut, '=', differenceMinutes, 'minutes');
        
        // Si l'arrivée est après l'heure de début, c'est un retard
        if (differenceMinutes > 0) {
            minutesRetardInput.value = differenceMinutes;
            console.log('Retard calculé:', differenceMinutes, 'minutes');
        } else {
            minutesRetardInput.value = 0;
            console.log('Pas de retard (arrivée à l\'heure ou en avance)');
        }
        
    } catch (error) {
        console.error('Erreur lors du calcul des minutes de retard:', error);
    }
    
    console.log('=== FIN CALCUL MINUTES RETARD ===');
}

// Fonction pour actualiser une ligne de présence après modification
function actualiserLignePresenceRepetition(presence) {
    console.log('=== ACTUALISATION LIGNE PRÉSENCE ===', presence);
    
    // Trouver la ligne correspondante
    const ligne = document.querySelector(`tr[data-membre-id="${presence.membre_id}"]`);
    if (!ligne) {
        console.error('Ligne non trouvée pour le membre:', presence.membre_id);
        return;
    }
    
    // Mettre à jour le statut
    const statutCell = ligne.querySelector('.statut-presence');
    if (statutCell) {
        const couleurs = {
            'present': 'green',
            'absent_justifie': 'blue', 
            'absent_non_justifie': 'red',
            'retard': 'orange'
        };
        
        const textes = {
            'present': 'Présent',
            'absent_justifie': 'Absent justifié',
            'absent_non_justifie': 'Absent non justifié', 
            'retard': 'En retard'
        };
        
        const couleur = couleurs[presence.statut] || 'gray';
        const texte = textes[presence.statut] || presence.statut;
        
        statutCell.className = `statut-presence px-3 py-1 text-xs font-medium rounded-full bg-${couleur}-500/20 text-${couleur}-400 border border-${couleur}-500/30`;
        statutCell.textContent = texte;
    }
    
    // Mettre à jour l'heure d'arrivée avec les minutes de retard si applicable
    const heureDiv = ligne.querySelector('.text-xs.text-white\\/60');
    if (heureDiv && presence.heure_arrivee) {
        const heure = presence.heure_arrivee.substring(11, 16); // Format HH:MM
        let contenuHeure = `🕐 ${heure}`;
        
        if (presence.minutes_retard > 0) {
            contenuHeure += `<span class="text-orange-400">(${presence.minutes_retard} min)</span>`;
        }
        
        heureDiv.innerHTML = contenuHeure;
    }
    
    // Mettre à jour l'affichage séparé des minutes de retard
    const minutesRetardDiv = ligne.querySelector('.text-xs.text-orange-400');
    if (minutesRetardDiv) {
        if (presence.statut === 'retard' && presence.minutes_retard > 0) {
            minutesRetardDiv.innerHTML = `⏳ ${presence.minutes_retard} min de retard`;
            minutesRetardDiv.style.display = '';
        } else {
            minutesRetardDiv.style.display = 'none';
        }
    }
    
    // Mettre à jour la justification
    const justificationDiv = ligne.querySelector('.text-xs.text-white\\/70');
    if (justificationDiv && presence.justification) {
        justificationDiv.innerHTML = `<i class="fas fa-comment mr-1"></i>${presence.justification.substring(0, 30)}${presence.justification.length > 30 ? '...' : ''}`;
        justificationDiv.title = presence.justification;
    }
    
    // Mettre à jour les données du bouton de modification
    const btnModifier = ligne.querySelector('.btn-modifier');
    if (btnModifier) {
        btnModifier.setAttribute('data-statut', presence.statut);
        btnModifier.setAttribute('data-heure-arrivee', presence.heure_arrivee ? presence.heure_arrivee.substring(11, 16) : '');
        btnModifier.setAttribute('data-minutes-retard', presence.minutes_retard || '');
        btnModifier.setAttribute('data-justification', presence.justification || '');
        btnModifier.setAttribute('data-notes-prestation', presence.notes_prestation || '');
    }
    
    console.log('Ligne mise à jour avec succès');
}

// Fonction pour modifier une présence existante
function modifierPresenceRepetition(membreId, nomMembre, statutActuel, heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation) {
    console.log('=== MODIFICATION PRÉSENCE RÉPÉTITION ===');
    console.log('Membre ID:', membreId);
    console.log('Nom membre:', nomMembre);
    console.log('Statut actuel:', statutActuel);
    
    // Ouvrir le modal
    ouvrirModalAjout();
    
    // Activer le mode modification
    const membreSelect = document.getElementById('membre_id');
    membreSelect.classList.add('hidden');
    membreSelect.removeAttribute('required'); // Supprimer l'attribut required quand masqué
    
    document.getElementById('nom_membre_modification').classList.remove('hidden');
    document.getElementById('nom_membre_modification').textContent = nomMembre;
    
    // Remplir le champ caché avec l'ID du membre
    const champCache = document.getElementById('membre_id_modification');
    if (champCache) {
        champCache.value = membreId;
        console.log('Champ caché membre_id_modification rempli avec:', membreId);
    } else {
        console.error('Champ caché membre_id_modification non trouvé !');
    }
    
    // Changer le titre du modal
    document.querySelector('#modalAjout h3').textContent = 'Modifier la Présence';
    
    // Ajouter un champ caché pour indiquer le mode modification
    let modeModification = document.getElementById('mode_modification');
    if (!modeModification) {
        modeModification = document.createElement('input');
        modeModification.type = 'hidden';
        modeModification.name = 'mode_modification';
        modeModification.id = 'mode_modification';
        modeModification.value = 'true';
        document.getElementById('formAjout').appendChild(modeModification);
    } else {
        modeModification.value = 'true';
    }
    
    // Pré-remplir les champs
    const statutSelect = document.getElementById('statut');
    const justificationTextarea = document.getElementById('justification');
    const notesPrestationTextarea = document.getElementById('notes_prestation');
    
    if (statutSelect) statutSelect.value = statutActuel;
    if (justificationTextarea) justificationTextarea.value = justification || '';
    if (notesPrestationTextarea) notesPrestationTextarea.value = notesPrestation || '';
    
    // Gérer les champs conditionnels
    gererAffichageChamps(statutActuel);
    
    // Pré-remplir les champs selon le statut
    if (statutActuel === 'present' || statutActuel === 'retard') {
        const heureInput = document.getElementById('heure_arrivee');
        const minutesInput = document.getElementById('minutes_retard');
        
        if (heureInput && heureArrivee) {
            heureInput.value = heureArrivee;
        }
        if (minutesInput && minutesRetard) {
            minutesInput.value = minutesRetard;
        }
    }
    
    if (statutActuel === 'present' || statutActuel === 'retard') {
        const prestationCheckbox = document.getElementById('prestation_effectuee');
        if (prestationCheckbox) {
            prestationCheckbox.checked = prestationEffectuee === 'true';
        }
    }
    
    console.log('=== FIN MODIFICATION PRÉSENCE RÉPÉTITION ===');
}

// Fonction pour actualiser une ligne de présence après modification
function actualiserLignePresenceRepetition(presence) {
    console.log('=== ACTUALISATION LIGNE PRÉSENCE ===');
    console.log('Présence reçue:', presence);
    
    // Trouver la ligne correspondante
    const ligne = document.querySelector(`tr[data-membre-id="${presence.membre_id}"]`);
    if (!ligne) {
        console.error('Ligne non trouvée pour le membre ID:', presence.membre_id);
        return;
    }
    
    // Mettre à jour le statut avec les minutes de retard si applicable
    const statutSpan = ligne.querySelector('.statut-presence');
    if (statutSpan) {
        let texteStatut = '';
        let couleurStatut = '';
        
        switch(presence.statut) {
            case 'present':
                texteStatut = 'Présent';
                couleurStatut = 'green';
                break;
            case 'absent_justifie':
                texteStatut = 'Absent (justifié)';
                couleurStatut = 'yellow';
                break;
            case 'absent_non_justifie':
                texteStatut = 'Absent (non justifié)';
                couleurStatut = 'red';
                break;
            case 'retard':
                texteStatut = 'En retard';
                couleurStatut = 'orange';
                break;
            default:
                texteStatut = 'Inconnu';
                couleurStatut = 'gray';
        }
        
        statutSpan.textContent = texteStatut;
        statutSpan.className = `statut-presence px-3 py-1 text-xs font-medium rounded-full bg-${couleurStatut}-500/20 text-${couleurStatut}-400 border border-${couleurStatut}-500/30`;
    }
    
    // Mettre à jour l'heure d'arrivée avec les minutes de retard si applicable
    const heureDiv = ligne.querySelector('.text-xs.text-white\\/60');
    if (heureDiv && presence.heure_arrivee) {
        const heure = presence.heure_arrivee.substring(11, 16); // Format HH:MM
        let contenuHeure = `🕐 ${heure}`;
        
        if (presence.minutes_retard > 0) {
            contenuHeure += `<span class="text-orange-400">(${presence.minutes_retard} min)</span>`;
        }
        
        heureDiv.innerHTML = contenuHeure;
    }
    
    // Mettre à jour l'affichage séparé des minutes de retard
    const minutesRetardDiv = ligne.querySelector('.text-xs.text-orange-400');
    if (minutesRetardDiv && presence.statut === 'retard' && presence.minutes_retard > 0) {
        minutesRetardDiv.innerHTML = `<i class="fas fa-hourglass-half mr-1"></i>${presence.minutes_retard} min de retard`;
    } else if (minutesRetardDiv && presence.statut !== 'retard') {
        minutesRetardDiv.style.display = 'none';
    }
    
    // Mettre à jour les boutons d'action avec les nouvelles données
    const boutonModifier = ligne.querySelector('.btn-modifier-presence');
    if (boutonModifier) {
        boutonModifier.setAttribute('data-statut-actuel', presence.statut);
        boutonModifier.setAttribute('data-heure-arrivee', presence.heure_arrivee ? presence.heure_arrivee.substring(11, 16) : '');
        boutonModifier.setAttribute('data-minutes-retard', presence.minutes_retard || 0);
        boutonModifier.setAttribute('data-justification', presence.justification || '');
        boutonModifier.setAttribute('data-prestation-effectuee', presence.prestation_effectuee ? 'true' : 'false');
        boutonModifier.setAttribute('data-notes-prestation', presence.notes_prestation || '');
    }
    
    console.log('=== FIN ACTUALISATION LIGNE PRÉSENCE ===');
}

// Fonction pour désactiver le mode modification
function desactiverModeModification() {
    // Afficher le select et masquer le nom du membre
    const membreSelect = document.getElementById('membre_id');
    membreSelect.classList.remove('hidden');
    membreSelect.setAttribute('required', 'required'); // Remettre l'attribut required
    
    document.getElementById('nom_membre_modification').classList.add('hidden');
    
    // Vider le champ caché de l'ID du membre
    document.getElementById('membre_id_modification').value = '';
    
    // Remettre le titre original
    document.querySelector('#modalAjout h3').textContent = 'Marquer une Présence';
    
    // Supprimer le champ caché du mode modification
    const inputHidden = document.getElementById('mode_modification');
    if (inputHidden) {
        inputHidden.remove();
    }
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalAjout') {
        fermerModalAjout();
    }
});

// Event listeners pour les boutons d'action
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation des event listeners...');
    
    // Vérifier que les fonctions existent
    if (typeof marquerPresence === 'undefined') {
        console.error('Fonction marquerPresence non définie !');
        return;
    }
    
    // Boutons présents
    document.querySelectorAll('.btn-marquer-presente').forEach(button => {
        button.addEventListener('click', function() {
            const membreId = this.getAttribute('data-member-id');
            console.log('Bouton présent cliqué pour membre:', membreId);
            marquerPresence(membreId, 'present');
        });
    });
    
    // Boutons absent justifié
    document.querySelectorAll('.btn-marquer-absent-justifie').forEach(button => {
        button.addEventListener('click', function() {
            const membreId = this.getAttribute('data-member-id');
            console.log('Bouton absent justifié cliqué pour membre:', membreId);
            marquerPresence(membreId, 'absent_justifie');
        });
    });
    
    // Boutons absent non justifié  
    document.querySelectorAll('.btn-marquer-absent-non-justifie').forEach(button => {
        button.addEventListener('click', function() {
            const membreId = this.getAttribute('data-member-id');
            console.log('Bouton absent non justifié cliqué pour membre:', membreId);
            marquerPresence(membreId, 'absent_non_justifie');
        });
    });
    
    // Boutons retard
    document.querySelectorAll('.btn-marquer-retard').forEach(button => {
        button.addEventListener('click', function() {
            const membreId = this.getAttribute('data-member-id');
            console.log('Bouton retard cliqué pour membre:', membreId);
            marquerPresence(membreId, 'retard');
        });
    });
    
    // Boutons modifier
    document.querySelectorAll('.btn-modifier-presence').forEach(button => {
        button.addEventListener('click', function() {
            const membreId = this.getAttribute('data-member-id');
            const statutActuel = this.getAttribute('data-statut-actuel');
            const nomMembre = this.getAttribute('data-nom-membre');
            const heureArrivee = this.getAttribute('data-heure-arrivee');
            const minutesRetard = this.getAttribute('data-minutes-retard');
            const justification = this.getAttribute('data-justification');
            const prestationEffectuee = this.getAttribute('data-prestation-effectuee');
            const notesPrestation = this.getAttribute('data-notes-prestation');
            
            console.log('Bouton modifier cliqué pour membre:', membreId, 'nom:', nomMembre, 'statut:', statutActuel);
            console.log('Données complètes:', {heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation});
            
            modifierPresenceRepetition(membreId, nomMembre, statutActuel, heureArrivee, minutesRetard, justification, prestationEffectuee, notesPrestation);
        });
    });
    
    // Event listener pour le changement de statut (maintenant géré par onchange)
    // const statutSelect = document.getElementById('statut');
    // if (statutSelect) {
    //     statutSelect.addEventListener('change', function() {
    //         console.log('Statut changé vers:', this.value);
    //         gererAffichageChamps(this.value);
    //     });
    // }
    
    console.log('Event listeners initialisés.');
});

// Gestion du formulaire modal
document.getElementById('formAjout').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const modeModification = document.getElementById('mode_modification');
    
    console.log('=== SOUMISSION FORMULAIRE MODAL ===');
    console.log('Mode modification:', modeModification ? modeModification.value : 'false');
    
    try {
        // Gérer le champ membre_id selon le mode
        const isModification = modeModification && modeModification.value === 'true';
        
        if (isModification) {
            // En mode modification, utiliser le champ caché
            const champCache = document.getElementById('membre_id_modification');
            const membreIdModification = champCache ? champCache.value : '';
            
            if (membreIdModification) {
                formData.set('membre_id', membreIdModification);
                console.log('Mode modification - Membre ID utilisé:', membreIdModification);
            } else {
                console.error('Mode modification - Aucun ID de membre trouvé dans le champ caché !');
            }
        } else {
            // En mode création, s'assurer que le champ membre_id est présent
            const membreIdSelect = document.getElementById('membre_id').value;
            if (membreIdSelect) {
                formData.set('membre_id', membreIdSelect);
                console.log('Mode création - Membre ID utilisé:', membreIdSelect);
            }
        }
        
        // Debug: Afficher les données du formulaire
        console.log('Données du formulaire:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        const response = await fetch(`/repetitions/${repetitionId}/marquer-presence`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.success) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            
            fermerModalAjout();
            
            // Si c'est une modification, actualiser la ligne
            if (modeModification && modeModification.value === 'true') {
                actualiserLignePresenceRepetition(data.presence);
            } else {
                // Sinon, recharger la page pour les nouvelles présences
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } else {
            console.error('Erreur de validation:', data.errors);
            
            // Afficher les erreurs de validation
            let messageErreur = data.message || 'Erreur de validation';
            if (data.errors) {
                const erreurs = Object.values(data.errors).flat();
                messageErreur += '\n' + erreurs.join('\n');
            }
            
            if (typeof alerteModerne !== 'undefined' && alerteModerne.error) {
                alerteModerne.error(messageErreur);
            } else {
                alert('Erreur: ' + messageErreur);
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.error) {
            alerteModerne.error('Erreur lors de l\'enregistrement de la présence');
        } else {
            alert('Erreur lors de l\'enregistrement de la présence');
        }
    }
});

// Fonction pour actualiser une ligne de présence dans le tableau des répétitions
function actualiserLignePresenceRepetition(presence) {
    console.log('=== ACTUALISATION LIGNE PRÉSENCE RÉPÉTITION ===');
    console.log('Presence:', presence);
    
    // Trouver la ligne correspondante dans le tableau
    const membreId = presence.membre_id;
    const lignePresence = document.querySelector(`tr[data-membre-id="${membreId}"]`);
    
    if (!lignePresence) {
        console.warn('Ligne de présence non trouvée pour le membre ID:', membreId);
        return;
    }
    
    // Mettre à jour le contenu de la cellule statut
    const celluleStatut = lignePresence.querySelector('td:nth-child(2)');
    if (celluleStatut) {
        const statutFrancais = {
            'present': 'Présent',
            'retard': 'En retard',
            'absent_justifie': 'Absent (justifié)',
            'absent_non_justifie': 'Absent (non justifié)'
        };
        
        const couleurStatut = {
            'present': 'green',
            'retard': 'orange',
            'absent_justifie': 'yellow',
            'absent_non_justifie': 'red'
        };
        
        let contenuHTML = `
            <div class="space-y-1">
                <span class="statut-presence px-3 py-1 text-xs font-medium rounded-full bg-${couleurStatut[presence.statut]}-500/20 text-${couleurStatut[presence.statut]}-400 border border-${couleurStatut[presence.statut]}-500/30">
                    ${statutFrancais[presence.statut] || presence.statut}
                </span>
        `;
        
        // Ajouter l'heure d'arrivée si disponible
        if (presence.heure_arrivee) {
            const heure = new Date(presence.heure_arrivee).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            contenuHTML += `
                <div class="text-xs text-white/60">
                    <i class="fas fa-clock mr-1"></i>${heure}
                </div>
            `;
        }
        
        // Ajouter les minutes de retard si disponibles
        if (presence.minutes_retard > 0) {
            contenuHTML += `
                <div class="text-xs text-orange-400">
                    <i class="fas fa-hourglass-half mr-1"></i>${presence.minutes_retard} min de retard
                </div>
            `;
        }
        
        // Ajouter la justification si disponible
        if (presence.justification) {
            const justificationTronquee = presence.justification.length > 30 ? 
                presence.justification.substring(0, 30) + '...' : 
                presence.justification;
            contenuHTML += `
                <div class="text-xs text-white/70 max-w-xs truncate" title="${presence.justification}">
                    <i class="fas fa-comment mr-1"></i>${justificationTronquee}
                </div>
            `;
        }
        
        // Ajouter la prestation effectuée si applicable
        if (presence.prestation_effectuee) {
            contenuHTML += `
                <div class="text-xs text-green-400">
                    <i class="fas fa-check-circle mr-1"></i>Prestation effectuée
                </div>
            `;
        }
        
        contenuHTML += '</div>';
        celluleStatut.innerHTML = contenuHTML;
    }
    
    // Mettre à jour le bouton de modification avec les nouvelles données
    const boutonModifier = lignePresence.querySelector('.btn-modifier-presence');
    if (boutonModifier) {
        const nomMembre = presence.membre ? `${presence.membre.nom} ${presence.membre.prenom}` : 'Membre inconnu';
        const heureArrivee = presence.heure_arrivee ? new Date(presence.heure_arrivee).toTimeString().slice(0, 5) : '';
        
        boutonModifier.setAttribute('data-statut-actuel', presence.statut);
        boutonModifier.setAttribute('data-nom-membre', nomMembre);
        boutonModifier.setAttribute('data-heure-arrivee', heureArrivee);
        boutonModifier.setAttribute('data-minutes-retard', presence.minutes_retard || 0);
        boutonModifier.setAttribute('data-justification', presence.justification || '');
        boutonModifier.setAttribute('data-prestation-effectuee', presence.prestation_effectuee ? 'true' : 'false');
        boutonModifier.setAttribute('data-notes-prestation', presence.notes_prestation || '');
    }
    
    console.log('Ligne de présence répétition mise à jour avec succès');
}
</script>
@endpush
@ e n d s e c t i o n 
 
 