@extends('layouts.app-with-sidebar')

@section('title', 'Nouvelle Activité - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-plus mr-3"></i>Nouvelle Activité</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('activites.index') }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Formulaire de création -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-white/20">
            <form id="formCreateActivite" class="space-y-6">
                @csrf
                
                <!-- Type et Nom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'activité <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="repetition" class="text-gray-800">Répétition</option>
                            <option value="prestation" class="text-gray-800">Prestation</option>
                            <option value="goudi_aldiouma" class="text-gray-800">Goudi Aldiouma</option>
                            <option value="formation" class="text-gray-800">Formation</option>
                            <option value="reunion" class="text-gray-800">Réunion</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-semibold text-white/80">Nom de l'activité <span class="text-red-400">*</span></label>
                        <input type="text" id="nom" name="nom" required
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Nom de l'activité">
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-white/80">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Description de l'activité"></textarea>
                </div>

                <!-- Dates (masquées pour les répétitions) -->
                <div id="sectionDates" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-semibold text-white/80">Date de début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_debut" name="date_debut"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-semibold text-white/80">Date de fin <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_fin" name="date_fin"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>
                </div>

                <!-- Lieu et Responsable -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="lieu" class="block text-sm font-semibold text-white/80">Lieu</label>
                        <input type="text" id="lieu" name="lieu"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Lieu de l'activité">
                    </div>

                    <div class="space-y-2">
                        <label for="responsable_id" class="block text-sm font-semibold text-white/80">Responsable</label>
                        <select id="responsable_id" name="responsable_id"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un responsable...</option>
                            @foreach($membres as $membre)
                            <option value="{{ $membre->id }}" class="text-gray-800">{{ $membre->nom }} {{ $membre->prenom }}</option>
                            @endforeach
                        </select>
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

                <!-- Type de création -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-white/80">Type de création</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                            <input type="radio" name="type_creation" value="simple" checked class="text-blue-500 focus:ring-blue-500/50">
                            <div>
                                <div class="text-white font-medium">Activité simple</div>
                                <div class="text-white/60 text-sm">Une seule occurrence</div>
                            </div>
                        </label>
                        <label class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                            <input type="radio" name="type_creation" value="repetition" class="text-blue-500 focus:ring-blue-500/50">
                            <div>
                                <div class="text-white font-medium">Avec répétitions</div>
                                <div class="text-white/60 text-sm">Génération automatique</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Section Répétitions (masquée par défaut) -->
                <div id="sectionRepetitions" class="hidden space-y-6">
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-blue-400 mb-4">
                            <i class="fas fa-redo mr-2"></i>Configuration des Répétitions
                        </h3>
                        
                        <!-- Horaires prédéfinis -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-white/80">Horaires prédéfinis</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="horaires_predefinis[]" value="mardi_soir" class="text-blue-500 focus:ring-blue-500/50">
                                    <div>
                                        <div class="text-white font-medium">Mardi Soir</div>
                                        <div class="text-white/60 text-sm">20h30 - 22h30</div>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="horaires_predefinis[]" value="dimanche_midi" class="text-blue-500 focus:ring-blue-500/50">
                                    <div>
                                        <div class="text-white font-medium">Dimanche Midi</div>
                                        <div class="text-white/60 text-sm">12h00 - 15h00</div>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="horaires_predefinis[]" value="jeudi_goudi" class="text-blue-500 focus:ring-blue-500/50">
                                    <div>
                                        <div class="text-white font-medium">Jeudi Goudi</div>
                                        <div class="text-white/60 text-sm">20h45 - 21h15</div>
                                    </div>
                                </label>
                            </div>
                            <label class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl border border-white/20 cursor-pointer hover:bg-white/10 transition-all duration-300">
                                <input type="checkbox" name="horaires_predefinis[]" value="personnalise" class="text-blue-500 focus:ring-blue-500/50">
                                <div>
                                    <div class="text-white font-medium">Horaires personnalisés</div>
                                    <div class="text-white/60 text-sm">Définir manuellement</div>
                                </div>
                            </label>
                        </div>

                        <!-- Horaires personnalisés (masqués par défaut) -->
                        <div id="horairesPersonnalises" class="hidden mt-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">Heure de début</label>
                                    <input type="time" name="heure_debut_perso" 
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">Heure de fin</label>
                                    <input type="time" name="heure_fin_perso" 
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                </div>
                            </div>
                        </div>

                        <!-- Jours de la semaine avec horaires spécifiques -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-white/80">Jours de répétition avec horaires</label>
                            <div class="space-y-3">
                                @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                <div class="jour-repetition p-4 bg-white/5 rounded-xl border border-white/20">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="flex items-center space-x-3 text-white/80 cursor-pointer">
                                            <input type="checkbox" name="jours_repetition[]" value="{{ $jour }}"
                                                   class="jour-checkbox rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500/50"
                                                   onchange="toggleHorairesJour('{{ $jour }}')">
                                            <span class="text-sm font-medium">{{ ucfirst($jour) }}</span>
                                        </label>
                                    </div>
                                    
                                    <!-- Horaires spécifiques pour ce jour -->
                                    <div id="horaires-{{ $jour }}" class="horaires-jour hidden grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs text-white/60 mb-1">Heure début</label>
                                            <input type="time" name="horaires[{{ $jour }}][debut]" 
                                                   class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-white/60 mb-1">Heure fin</label>
                                            <input type="time" name="horaires[{{ $jour }}][fin]" 
                                                   class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Période de génération -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-white/80">Période de génération</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">Date de début</label>
                                    <input type="date" name="date_debut_repetition" 
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">Date de fin</label>
                                    <input type="date" name="date_fin_repetition" 
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                </div>
                            </div>
                            <p class="text-xs text-white/60">Les répétitions seront générées pour chaque jour sélectionné dans cette période</p>
                        </div>
                    </div>
                </div>

                <!-- Configuration (optionnel) -->
                <div class="space-y-2">
                    <label for="configuration" class="block text-sm font-semibold text-white/80">Configuration (JSON)</label>
                    <textarea id="configuration" name="configuration" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder='{"repetition_hebdomadaire": true, "jours": ["lundi", "mercredi"]}'></textarea>
                    <p class="text-xs text-white/60">Configuration optionnelle au format JSON pour les activités récurrentes</p>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('activites.index') }}"
                       class="px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Créer l'activité
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
// Gestion globale des erreurs JavaScript (logs uniquement)
window.addEventListener('error', function(e) {
    console.error('=== ERREUR JAVASCRIPT GLOBALE ===');
    console.error('Message:', e.message);
    console.error('Fichier:', e.filename);
    console.error('Ligne:', e.lineno);
    console.error('Colonne:', e.colno);
    console.error('Erreur complète:', e.error);
    if (e.error && e.error.stack) {
        console.error('Stack trace:', e.error.stack);
    }
    console.error('================================');
});

// Gestion des promesses rejetées (logs uniquement)
window.addEventListener('unhandledrejection', function(e) {
    console.error('=== PROMESSE REJETÉE ===');
    console.error('Raison:', e.reason);
    console.error('Type:', typeof e.reason);
    if (e.reason && e.reason.stack) {
        console.error('Stack trace:', e.reason.stack);
    }
    console.error('========================');
});

// Variables globales
let activiteCreee = null;
let form = null;

// Gestion de l'affichage des sections
function initialiserApplication() {
    console.log('=== INITIALISATION APPLICATION ===');
    console.log('Début de l\'initialisation...');
    
    // Recherche directe du formulaire
    console.log('Recherche du formulaire avec ID: formCreateActivite');
    form = document.getElementById('formCreateActivite');
    console.log('Résultat de getElementById:', form);
    
    if (!form) {
        console.error('ERREUR: Formulaire formCreateActivite non trouvé !');
        console.log('Tentative de recherche alternative...');
        
        // Recherche alternative
        const forms = document.querySelectorAll('form');
        console.log('Nombre de formulaires trouvés:', forms.length);
        
        if (forms.length > 0) {
            form = forms[0];
            console.log('✅ Premier formulaire trouvé:', form);
        } else {
            console.error('❌ Aucun formulaire trouvé, arrêt de l\'initialisation');
            return;
        }
    }
    
    console.log('✅ Formulaire trouvé:', form);
    console.log('ID du formulaire:', form.id);
    console.log('Classes du formulaire:', form.className);
    
    // Gestion du formulaire de création
    console.log('=== ATTACHEMENT EVENT LISTENER ===');
    console.log('Attachement du gestionnaire de soumission...');
    form.addEventListener('submit', function(e) {
        console.log('=== SOUMISSION FORMULAIRE ===');
        e.preventDefault();
        console.log('✅ Formulaire soumis !');
        
        const formData = new FormData(this);
        const typeCreation = formData.get('type_creation');
        console.log('Type de création:', typeCreation);
        
        // Validation de la configuration JSON si fournie
        const configuration = document.getElementById('configuration').value.trim();
        if (configuration) {
            try {
                JSON.parse(configuration);
            } catch (error) {
                if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                    alerteModerne.error('Format JSON invalide pour la configuration');
                } else {
                    alert('Format JSON invalide pour la configuration');
                }
                return;
            }
        }
        
        // Si c'est une création avec répétitions, traiter différemment
        if (typeCreation === 'repetition') {
            console.log('🔄 Création avec répétitions');
            creerActiviteAvecRepetitions(formData);
        } else {
            console.log('📝 Création simple');
            creerActiviteSimple(formData);
        }
        console.log('================================');
    });
    console.log('✅ Gestionnaire de soumission attaché');
    
    // Vérification du bouton submit
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        console.log('✅ Bouton submit trouvé:', submitButton);
    } else {
        console.error('❌ Bouton submit non trouvé');
    }
    
    // Gestion du type de création
    const typeCreationRadios = document.querySelectorAll('input[name="type_creation"]');
    const sectionRepetitions = document.getElementById('sectionRepetitions');
    const sectionDates = document.getElementById('sectionDates');
    
    console.log('=== CONFIGURATION BOUTONS RADIO ===');
    console.log('Boutons radio trouvés:', typeCreationRadios.length);
    console.log('Section répétitions trouvée:', sectionRepetitions);
    console.log('Section dates trouvée:', sectionDates);
    
    typeCreationRadios.forEach((radio, index) => {
        console.log(`Radio ${index}:`, radio.value, radio.checked);
        radio.addEventListener('change', function() {
            console.log('=== CHANGEMENT BOUTON RADIO ===');
            console.log('Changement détecté:', this.value, this.checked);
            
            const dateDebut = document.getElementById('date_debut');
            const dateFin = document.getElementById('date_fin');
            
            if (this.value === 'repetition') {
                console.log('✅ Affichage section répétitions');
                sectionRepetitions.classList.remove('hidden');
                sectionDates.classList.add('hidden');
                // Désactiver la validation des dates pour les répétitions
                dateDebut.removeAttribute('required');
                dateFin.removeAttribute('required');
                console.log('Dates désactivées pour les répétitions');
            } else {
                console.log('❌ Masquage section répétitions');
                sectionRepetitions.classList.add('hidden');
                sectionDates.classList.remove('hidden');
                // Activer la validation des dates pour les activités simples
                dateDebut.setAttribute('required', 'required');
                dateFin.setAttribute('required', 'required');
                console.log('Dates activées pour les activités simples');
            }
            console.log('Classes section répétitions après:', sectionRepetitions.className);
            console.log('Classes section dates après:', sectionDates.className);
            console.log('================================');
        });
    });
    
    // Gestion des horaires prédéfinis
    const horairesPredefinisCheckboxes = document.querySelectorAll('input[name="horaires_predefinis[]"]');
    const horairesPersonnalises = document.getElementById('horairesPersonnalises');
    
    horairesPredefinisCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Vérifier si "personnalise" est coché
            const personnaliseCheckbox = document.querySelector('input[name="horaires_predefinis[]"][value="personnalise"]');
            
            if (personnaliseCheckbox && personnaliseCheckbox.checked) {
                horairesPersonnalises.classList.remove('hidden');
            } else {
                horairesPersonnalises.classList.add('hidden');
            }
            
            // Auto-remplir les jours et horaires selon les sélections
            autoRemplirJoursEtHoraires();
        });
    });
    
    // Fonction pour auto-remplir les jours et horaires
    function autoRemplirJoursEtHoraires() {
        const horairesPredefinis = Array.from(document.querySelectorAll('input[name="horaires_predefinis[]"]:checked'))
            .map(cb => cb.value);
        
        // Décocher tous les jours d'abord
        document.querySelectorAll('input[name="jours_repetition[]"]').forEach(cb => cb.checked = false);
        
        // Vider tous les horaires
        document.querySelectorAll('input[name^="horaires["]').forEach(input => input.value = '');
        
        // Appliquer les horaires prédéfinis
        horairesPredefinis.forEach(horaire => {
            switch(horaire) {
                case 'mardi_soir':
                    document.querySelector('input[name="jours_repetition[]"][value="mardi"]').checked = true;
                    document.querySelector('input[name="horaires[mardi][debut]"]').value = '20:30';
                    document.querySelector('input[name="horaires[mardi][fin]"]').value = '22:30';
                    break;
                case 'dimanche_midi':
                    document.querySelector('input[name="jours_repetition[]"][value="dimanche"]').checked = true;
                    document.querySelector('input[name="horaires[dimanche][debut]"]').value = '12:00';
                    document.querySelector('input[name="horaires[dimanche][fin]"]').value = '15:00';
                    break;
                case 'jeudi_goudi':
                    document.querySelector('input[name="jours_repetition[]"][value="jeudi"]').checked = true;
                    document.querySelector('input[name="horaires[jeudi][debut]"]').value = '20:45';
                    document.querySelector('input[name="horaires[jeudi][fin]"]').value = '21:15';
                    break;
            }
        });
    }
    
    // Auto-sélection des jours selon le type d'activité
    const typeActivite = document.getElementById('type');
    if (typeActivite) {
        typeActivite.addEventListener('change', function() {
            autoSelectionJours(this.value);
        });
    }
    
    // Configurer la validation des dates
    configurerValidationDates();
    
    // Validation de la configuration JSON en temps réel
    const configInput = document.getElementById('configuration');
    if (configInput) {
        configInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value) {
                try {
                    JSON.parse(value);
                    this.classList.remove('border-red-500');
                    this.classList.add('border-white/20');
                } catch (error) {
                    this.classList.remove('border-white/20');
                    this.classList.add('border-red-500');
                }
            }
        });
        console.log('✅ Validation JSON configurée');
    } else {
        console.log('⚠️ Champ configuration non trouvé');
    }
}

// Fonction pour remplir les horaires prédéfinis
function remplirHorairesPredefinis(type) {
    const heureDebut = document.querySelector('input[name="heure_debut_perso"]');
    const heureFin = document.querySelector('input[name="heure_fin_perso"]');
    
    switch(type) {
        case 'mardi_soir':
            heureDebut.value = '20:30';
            heureFin.value = '22:30';
            break;
        case 'dimanche_midi':
            heureDebut.value = '12:00';
            heureFin.value = '15:00';
            break;
        case 'jeudi_goudi':
            heureDebut.value = '20:45';
            heureFin.value = '21:15';
            break;
    }
}

// Fonction pour auto-sélectionner les jours selon le type d'activité
function autoSelectionJours(type) {
    // Décocher tous les horaires prédéfinis d'abord
    document.querySelectorAll('input[name="horaires_predefinis[]"]').forEach(cb => cb.checked = false);
    
    switch(type) {
        case 'repetition':
            // Cocher les horaires prédéfinis pour répétition
            document.querySelector('input[name="horaires_predefinis[]"][value="mardi_soir"]').checked = true;
            document.querySelector('input[name="horaires_predefinis[]"][value="dimanche_midi"]').checked = true;
            autoRemplirJoursEtHoraires();
            break;
        case 'goudi_aldiouma':
            // Cocher l'horaire prédéfini pour goudi aldiouma
            document.querySelector('input[name="horaires_predefinis[]"][value="jeudi_goudi"]').checked = true;
            autoRemplirJoursEtHoraires();
            break;
    }
}

// Fonction pour afficher/masquer les horaires d'un jour
function toggleHorairesJour(jour) {
    const checkbox = document.querySelector(`input[name="jours_repetition[]"][value="${jour}"]`);
    const horairesDiv = document.getElementById(`horaires-${jour}`);
    
    if (checkbox.checked) {
        horairesDiv.classList.remove('hidden');
    } else {
        horairesDiv.classList.add('hidden');
        // Vider les horaires quand on décoche
        document.querySelector(`input[name="horaires[${jour}][debut]"]`).value = '';
        document.querySelector(`input[name="horaires[${jour}][fin]"]`).value = '';
    }
}


// Fonction pour créer une activité simple
function creerActiviteSimple(formData) {
    console.log('=== CRÉATION ACTIVITÉ SIMPLE ===');
    console.log('Début création activité simple');
    console.log('FormData:', Array.from(formData.entries()));
    fetch('{{ route("activites.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        console.log('=== RÉPONSE SERVEUR ===');
        console.log('Réponse reçue:', response);
        console.log('Status:', response.status);
        console.log('Status OK:', response.ok);
        return response.json();
    })
    .then(data => {
        console.log('=== DONNÉES REÇUES ===');
        console.log('Données reçues:', data);
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            // Rediriger vers la page de détails de l'activité créée
            window.location.href = `/activites/${data.activite.id}`;
        } else {
            console.error('=== ÉCHEC DE CRÉATION ===');
            console.error('Échec de création:', data);
            console.error('Message d\'erreur:', data.message);
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
            // Afficher les erreurs de validation si présentes
            if (data.errors) {
                console.error('=== ERREURS DE VALIDATION ===');
                console.error('Erreurs de validation:', data.errors);
                console.error('Détail des erreurs:', JSON.stringify(data.errors, null, 2));
            }
        }
    })
    .catch(error => {
        console.error('=== ERREUR RÉSEAU ===');
        console.error('Erreur complète:', error);
        console.error('Message:', error.message);
        console.error('Type:', typeof error);
        console.error('Stack:', error.stack);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la création de l\'activité: ' + error.message);
        } else {
            alert('Erreur lors de la création de l\'activité: ' + error.message);
        }
        console.error('========================');
    });
}

// Fonction pour créer une activité avec répétitions
function creerActiviteAvecRepetitions(formData) {
    console.log('=== CRÉATION ACTIVITÉ AVEC RÉPÉTITIONS ===');
    console.log('Début création activité avec répétitions');
    // D'abord créer l'activité
    const activiteData = new FormData();
    activiteData.append('type', formData.get('type'));
    activiteData.append('nom', formData.get('nom'));
    activiteData.append('description', formData.get('description'));
    activiteData.append('lieu', formData.get('lieu'));
    activiteData.append('responsable_id', formData.get('responsable_id'));
    activiteData.append('statut', formData.get('statut'));
    activiteData.append('configuration', formData.get('configuration'));
    
    // Ne pas envoyer les dates pour les activités avec répétitions
    // Le serveur utilisera des dates par défaut
    
    fetch('{{ route("activites.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: activiteData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            activiteCreee = data.activite;
            // Maintenant générer les répétitions
            genererRepetitions(data.activite.id, formData);
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
            alerteModerne.error('Erreur lors de la création de l\'activité');
        } else {
            alert('Erreur lors de la création de l\'activité');
        }
    });
}

// Fonction pour générer les répétitions
function genererRepetitions(activiteId, formData) {
    // Collecter les horaires spécifiques pour chaque jour
    const horairesParJour = {};
    const joursSelectionnes = formData.getAll('jours_repetition[]');
    
    joursSelectionnes.forEach(jour => {
        const heureDebut = document.querySelector(`input[name="horaires[${jour}][debut]"]`).value;
        const heureFin = document.querySelector(`input[name="horaires[${jour}][fin]"]`).value;
        
        if (heureDebut && heureFin) {
            horairesParJour[jour] = {
                debut: heureDebut,
                fin: heureFin
            };
        }
    });
    
    // Vérifier qu'au moins un jour a des horaires définis
    if (Object.keys(horairesParJour).length === 0) {
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Veuillez définir les horaires pour au moins un jour sélectionné');
        } else {
            alert('Veuillez définir les horaires pour au moins un jour sélectionné');
        }
        return;
    }
    
    const repetitionData = new FormData();
    
    console.log('=== DONNÉES POUR GÉNÉRATION RÉPÉTITIONS ===');
    console.log('Date début:', formData.get('date_debut_repetition'));
    console.log('Date fin:', formData.get('date_fin_repetition'));
    console.log('Jours sélectionnés:', joursSelectionnes);
    console.log('Horaires par jour:', horairesParJour);
    console.log('Lieu:', formData.get('lieu'));
    console.log('Responsable ID:', formData.get('responsable_id'));
    
    repetitionData.append('date_debut', formData.get('date_debut_repetition'));
    repetitionData.append('date_fin', formData.get('date_fin_repetition'));
    
    // Ajouter chaque jour sélectionné individuellement
    joursSelectionnes.forEach(jour => {
        repetitionData.append('jours_semaine[]', jour);
    });
    
    repetitionData.append('horaires_par_jour', JSON.stringify(horairesParJour));
    repetitionData.append('lieu', formData.get('lieu'));
    repetitionData.append('responsable_id', formData.get('responsable_id'));
    
    console.log('=== ENVOI VERS SERVEUR ===');
    console.log('URL:', `/activites/${activiteId}/repetitions/generer`);
    console.log('Données FormData:');
    for (let [key, value] of repetitionData.entries()) {
        console.log(`${key}:`, value);
    }
    
    fetch(`/activites/${activiteId}/repetitions/generer`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: repetitionData
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
            console.log('✅ Répétitions générées avec succès');
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(`Activité créée avec ${data.repetitions_creees} répétitions`);
            } else {
                alert(`Succès: Activité créée avec ${data.repetitions_creees} répétitions`);
            }
            // Rediriger vers la page des répétitions
            window.location.href = `/activites/${activiteId}/repetitions`;
        } else {
            console.error('❌ Erreur génération:', data.message);
            console.error('Erreurs détaillées:', data.errors);
            
            // Afficher les erreurs détaillées
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    console.error(`Erreur ${field}:`, data.errors[field]);
                });
            }
            
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('❌ Erreur catch:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
            alerteModerne.error('Erreur lors de la génération des répétitions');
        } else {
            alert('Erreur lors de la génération des répétitions');
        }
    });
}


// Fonction pour configurer la validation des dates
function configurerValidationDates() {
    console.log('=== CONFIGURATION VALIDATION DATES ===');
    
    // Validation des dates pour les activités simples
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    if (dateDebutInput) {
        dateDebutInput.addEventListener('change', function() {
            const dateDebut = new Date(this.value);
            
            if (dateDebut && dateFinInput && dateFinInput.value) {
                const dateFin = new Date(dateFinInput.value);
                if (dateFin <= dateDebut) {
                    if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                        alerteModerne.warning('La date de fin doit être postérieure à la date de début');
                    } else {
                        alert('La date de fin doit être postérieure à la date de début');
                    }
                    dateFinInput.value = '';
                }
            }
        });
        console.log('✅ Validation date début configurée');
    }
    
    if (dateFinInput) {
        dateFinInput.addEventListener('change', function() {
            const dateFin = new Date(this.value);
            
            if (dateFin && dateDebutInput && dateDebutInput.value) {
                const dateDebut = new Date(dateDebutInput.value);
                if (dateFin <= dateDebut) {
                    if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                        alerteModerne.warning('La date de fin doit être postérieure à la date de début');
                    } else {
                        alert('La date de fin doit être postérieure à la date de début');
                    }
                    this.value = '';
                }
            }
        });
        console.log('✅ Validation date fin configurée');
    }
    
    // Validation des dates pour les répétitions
    const dateDebutRepetition = document.querySelector('input[name="date_debut_repetition"]');
    const dateFinRepetition = document.querySelector('input[name="date_fin_repetition"]');
    
    if (dateDebutRepetition) {
        dateDebutRepetition.addEventListener('change', function() {
            const dateDebut = new Date(this.value);
            if (dateFinRepetition.value) {
                const dateFin = new Date(dateFinRepetition.value);
                if (dateFin <= dateDebut) {
                    if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                        alerteModerne.warning('La date de fin de répétition doit être postérieure à la date de début');
                    } else {
                        alert('La date de fin de répétition doit être postérieure à la date de début');
                    }
                    dateFinRepetition.value = '';
                }
            }
        });
        console.log('✅ Validation date début répétition configurée');
    }
    
    if (dateFinRepetition) {
        dateFinRepetition.addEventListener('change', function() {
            const dateFin = new Date(this.value);
            if (dateDebutRepetition.value) {
                const dateDebut = new Date(dateDebutRepetition.value);
                if (dateFin <= dateDebut) {
                    if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                        alerteModerne.warning('La date de fin de répétition doit être postérieure à la date de début');
                    } else {
                        alert('La date de fin de répétition doit être postérieure à la date de début');
                    }
                    this.value = '';
                }
            }
        });
        console.log('✅ Validation date fin répétition configurée');
    }
}

// Initialisation directe
function initialiserTout() {
    console.log('=== INITIALISATION COMPLÈTE ===');
    
    // Initialiser l'application principale
    initialiserApplication();
}

// Initialisation directe et simple
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CONTENT LOADED ===');
    initialiserTout();
});
</script>
@endpush
