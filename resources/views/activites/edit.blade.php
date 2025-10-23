@extends('layouts.app-with-sidebar')

@section('title', 'Modifier l\'Activité - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-edit mr-3"></i>Modifier l'Activité</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('activites.show', $activite->id) }}" 
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

        <!-- Formulaire de modification -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-white/20">
            <form id="formEditActivite" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Type et Nom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'activité <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="repetition" class="text-gray-800" {{ $activite->type === 'repetition' ? 'selected' : '' }}>Répétition</option>
                            <option value="prestation" class="text-gray-800" {{ $activite->type === 'prestation' ? 'selected' : '' }}>Prestation</option>
                            <option value="goudi_aldiouma" class="text-gray-800" {{ $activite->type === 'goudi_aldiouma' ? 'selected' : '' }}>Goudi Aldiouma</option>
                            <option value="formation" class="text-gray-800" {{ $activite->type === 'formation' ? 'selected' : '' }}>Formation</option>
                            <option value="reunion" class="text-gray-800" {{ $activite->type === 'reunion' ? 'selected' : '' }}>Réunion</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-semibold text-white/80">Nom de l'activité <span class="text-red-400">*</span></label>
                        <input type="text" id="nom" name="nom" required value="{{ $activite->nom }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Nom de l'activité">
                    </div>
                </div>

                <!-- Type de création -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-white/80">Type de création</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="type_creation" value="simple" class="mr-2 text-blue-500" checked>
                            <span class="text-white">Activité simple</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type_creation" value="repetition" class="mr-2 text-blue-500">
                            <span class="text-white">Avec répétitions</span>
                        </label>
                    </div>
                </div>

                <!-- Section Répétitions (cachée par défaut) -->
                <div id="sectionRepetitions" class="space-y-6 hidden">
                    <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                        <h3 class="text-lg font-semibold text-white mb-4">Configuration des répétitions</h3>
                        
                        <!-- Horaires prédéfinis -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-white/80">Horaires prédéfinis</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="horaires_predefinis[]" value="mardi_soir" class="mr-2 text-blue-500">
                                    <span class="text-white text-sm">Mardi Soir</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="horaires_predefinis[]" value="dimanche_midi" class="mr-2 text-blue-500">
                                    <span class="text-white text-sm">Dimanche Midi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="horaires_predefinis[]" value="jeudi_goudi" class="mr-2 text-blue-500">
                                    <span class="text-white text-sm">Jeudi Goudi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="horaires_predefinis[]" value="personnalise" class="mr-2 text-blue-500">
                                    <span class="text-white text-sm">Personnalisé</span>
                                </label>
                            </div>
                        </div>

                        <!-- Horaires personnalisés -->
                        <div id="horairesPersonnalises" class="space-y-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="heure_debut_perso" class="block text-sm font-semibold text-white/80">Heure de début</label>
                                    <input type="time" id="heure_debut_perso" name="heure_debut_perso"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                                </div>
                                <div>
                                    <label for="heure_fin_perso" class="block text-sm font-semibold text-white/80">Heure de fin</label>
                                    <input type="time" id="heure_fin_perso" name="heure_fin_perso"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                                </div>
                            </div>
                        </div>

                        <!-- Jours de répétition avec horaires -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-white/80">Jours de répétition avec horaires</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                                @endphp
                                @foreach($jours as $jour)
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="jours_repetition[]" value="{{ $jour }}" class="mr-2 text-blue-500">
                                        <span class="text-white capitalize">{{ $jour }}</span>
                                    </label>
                                    <div class="flex space-x-2">
                                        <input type="time" name="horaires[{{ $jour }}][debut]" 
                                               class="w-20 px-2 py-1 bg-white/10 border border-white/20 rounded text-white text-sm" 
                                               placeholder="Début">
                                        <input type="time" name="horaires[{{ $jour }}][fin]" 
                                               class="w-20 px-2 py-1 bg-white/10 border border-white/20 rounded text-white text-sm" 
                                               placeholder="Fin">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Période de génération -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-white/80">Période de génération</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="date_debut_repetition" class="block text-sm font-semibold text-white/80">Date de début</label>
                                    <input type="date" id="date_debut_repetition" name="date_debut_repetition"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                                </div>
                                <div>
                                    <label for="date_fin_repetition" class="block text-sm font-semibold text-white/80">Date de fin</label>
                                    <input type="date" id="date_fin_repetition" name="date_fin_repetition"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-white/80">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Description de l'activité">{{ $activite->description }}</textarea>
                </div>

                <!-- Dates -->
                <div id="sectionDates" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-semibold text-white/80">Date de début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required 
                               value="{{ $activite->date_debut ? $activite->date_debut->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-semibold text-white/80">Date de fin <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required
                               value="{{ $activite->date_fin ? $activite->date_fin->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>
                </div>

                <!-- Lieu et Responsable -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="lieu" class="block text-sm font-semibold text-white/80">Lieu</label>
                        <input type="text" id="lieu" name="lieu" value="{{ $activite->lieu }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Lieu de l'activité">
                    </div>

                    <div class="space-y-2">
                        <label for="responsable_id" class="block text-sm font-semibold text-white/80">Responsable</label>
                        <select id="responsable_id" name="responsable_id"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un responsable...</option>
                            @foreach($membres as $membre)
                            <option value="{{ $membre->id }}" class="text-gray-800" {{ $activite->responsable_id == $membre->id ? 'selected' : '' }}>
                                {{ $membre->nom }} {{ $membre->prenom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Statut -->
                <div class="space-y-2">
                    <label for="statut" class="block text-sm font-semibold text-white/80">Statut <span class="text-red-400">*</span></label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="planifie" class="text-gray-800" {{ $activite->statut === 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="confirme" class="text-gray-800" {{ $activite->statut === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                        <option value="en_cours" class="text-gray-800" {{ $activite->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" class="text-gray-800" {{ $activite->statut === 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="annule" class="text-gray-800" {{ $activite->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <!-- Configuration (optionnel) -->
                <div class="space-y-2">
                    <label for="configuration" class="block text-sm font-semibold text-white/80">Configuration (JSON)</label>
                    <textarea id="configuration" name="configuration" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder='{"repetition_hebdomadaire": true, "jours": ["lundi", "mercredi"]}'>{{ $activite->configuration ? json_encode($activite->configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '' }}</textarea>
                    <p class="text-xs text-white/60">Configuration optionnelle au format JSON pour les activités récurrentes</p>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('activites.show', $activite->id) }}"
                       class="px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
// Gestion du type de création
document.addEventListener('DOMContentLoaded', function() {
    const typeCreationRadios = document.querySelectorAll('input[name="type_creation"]');
    const sectionRepetitions = document.getElementById('sectionRepetitions');
    const sectionDates = document.getElementById('sectionDates');
    
    // Fonction pour gérer l'affichage des sections
    function toggleSections() {
        const selectedType = document.querySelector('input[name="type_creation"]:checked').value;
        const dateDebutInput = document.getElementById('date_debut');
        const dateFinInput = document.getElementById('date_fin');
        
        if (selectedType === 'repetition') {
            sectionRepetitions.classList.remove('hidden');
            sectionDates.classList.add('hidden');
            // Retirer l'attribut required des dates
            if (dateDebutInput) dateDebutInput.removeAttribute('required');
            if (dateFinInput) dateFinInput.removeAttribute('required');
        } else {
            sectionRepetitions.classList.add('hidden');
            sectionDates.classList.remove('hidden');
            // Ajouter l'attribut required aux dates
            if (dateDebutInput) dateDebutInput.setAttribute('required', 'required');
            if (dateFinInput) dateFinInput.setAttribute('required', 'required');
        }
    }
    
    // Écouter les changements de type de création
    typeCreationRadios.forEach(radio => {
        radio.addEventListener('change', toggleSections);
    });
    
    // Initialiser l'affichage
    toggleSections();
    
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
    typeActivite.addEventListener('change', function() {
        if (this.value === 'repetition') {
            // Cocher les horaires prédéfinis pour répétition
            document.querySelector('input[name="horaires_predefinis[]"][value="mardi_soir"]').checked = true;
            document.querySelector('input[name="horaires_predefinis[]"][value="dimanche_midi"]').checked = true;
            autoRemplirJoursEtHoraires();
        } else if (this.value === 'goudi_aldiouma') {
            // Cocher l'horaire prédéfini pour goudi aldiouma
            document.querySelector('input[name="horaires_predefinis[]"][value="jeudi_goudi"]').checked = true;
            autoRemplirJoursEtHoraires();
        }
    });
});

// Gestion du formulaire de modification
document.getElementById('formEditActivite').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('=== SOUMISSION FORMULAIRE MODIFICATION ===');
    
    const formData = new FormData(this);
    
    // Afficher toutes les données du formulaire
    console.log('Données du formulaire:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }
    
    // Validation de la configuration JSON si fournie
    const configuration = document.getElementById('configuration').value.trim();
    if (configuration) {
        try {
            JSON.parse(configuration);
            console.log('Configuration JSON valide');
        } catch (error) {
            console.error('Erreur JSON:', error);
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.error('Format JSON invalide pour la configuration');
            } else {
                alert('Format JSON invalide pour la configuration');
            }
            return;
        }
    }
    
    console.log('Envoi de la requête vers:', '{{ route("activites.update", $activite->id) }}');
    
    // Ajouter _method pour Laravel
    formData.append('_method', 'PUT');
    
    fetch('{{ route("activites.update", $activite->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
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
            console.log('✅ Mise à jour réussie');
            if (typeof alerteModerne !== 'undefined' && alerteModerne.toast) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            // Rediriger vers la page de détails de l'activité
            window.location.href = '{{ route("activites.show", $activite->id) }}';
        } else {
            console.error('❌ Erreur mise à jour:', data.message);
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
            alerteModerne.error('Erreur lors de la mise à jour de l\'activité');
        } else {
            alert('Erreur lors de la mise à jour de l\'activité');
        }
    });
});

// Validation en temps réel des dates
document.getElementById('date_debut').addEventListener('change', function() {
    const dateDebut = new Date(this.value);
    const dateFinInput = document.getElementById('date_fin');
    
    if (dateDebut && dateFinInput.value) {
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

document.getElementById('date_fin').addEventListener('change', function() {
    const dateFin = new Date(this.value);
    const dateDebutInput = document.getElementById('date_debut');
    
    if (dateFin && dateDebutInput.value) {
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

// Validation de la configuration JSON en temps réel
document.getElementById('configuration').addEventListener('blur', function() {
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
</script>
@endsection
