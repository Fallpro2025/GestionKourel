@extends('layouts.app-with-sidebar')

@section('title', 'Modifier l\'Événement - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-edit mr-3"></i>Modifier l'Événement</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('evenements.show', $evenement->id) }}" 
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
            <form id="formEditEvenement" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Nom et Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-semibold text-white/80">Nom de l'événement <span class="text-red-400">*</span></label>
                        <input type="text" id="nom" name="nom" required value="{{ $evenement->nom }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Nom de l'événement">
                    </div>

                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'événement <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="magal" class="text-gray-800" {{ $evenement->type === 'magal' ? 'selected' : '' }}>Magal</option>
                            <option value="gamou" class="text-gray-800" {{ $evenement->type === 'gamou' ? 'selected' : '' }}>Gamou</option>
                            <option value="promokhane" class="text-gray-800" {{ $evenement->type === 'promokhane' ? 'selected' : '' }}>Promokhane</option>
                            <option value="conference" class="text-gray-800" {{ $evenement->type === 'conference' ? 'selected' : '' }}>Conférence</option>
                            <option value="formation" class="text-gray-800" {{ $evenement->type === 'formation' ? 'selected' : '' }}>Formation</option>
                            <option value="autre" class="text-gray-800" {{ $evenement->type === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-white/80">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Description de l'événement">{{ $evenement->description }}</textarea>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-semibold text-white/80">Date de début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required 
                               value="{{ $evenement->date_debut->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-semibold text-white/80">Date de fin <span class="text-red-400">*</span></label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required
                               value="{{ $evenement->date_fin->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                    </div>
                </div>

                <!-- Lieu et Budget -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="lieu" class="block text-sm font-semibold text-white/80">Lieu</label>
                        <input type="text" id="lieu" name="lieu" value="{{ $evenement->lieu }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Lieu de l'événement">
                    </div>

                    <div class="space-y-2">
                        <label for="budget" class="block text-sm font-semibold text-white/80">Budget (FCFA)</label>
                        <input type="number" id="budget" name="budget" min="0" step="100" value="{{ $evenement->budget }}"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                               placeholder="Budget de l'événement">
                    </div>
                </div>

                <!-- Statut -->
                <div class="space-y-2">
                    <label for="statut" class="block text-sm font-semibold text-white/80">Statut <span class="text-red-400">*</span></label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="planifie" class="text-gray-800" {{ $evenement->statut === 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="confirme" class="text-gray-800" {{ $evenement->statut === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                        <option value="en_cours" class="text-gray-800" {{ $evenement->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" class="text-gray-800" {{ $evenement->statut === 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="annule" class="text-gray-800" {{ $evenement->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <!-- Configuration (optionnel) -->
                <div class="space-y-2">
                    <label for="configuration" class="block text-sm font-semibold text-white/80">Configuration (JSON)</label>
                    <textarea id="configuration" name="configuration" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder='{"dress_code": "tenue traditionnelle", "transport": "organise"}'>{{ $evenement->configuration ? json_encode($evenement->configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '' }}</textarea>
                    <p class="text-xs text-white/60">Configuration optionnelle au format JSON pour les détails de l'événement</p>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('evenements.show', $evenement->id) }}"
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
// Gestion du formulaire de modification
document.getElementById('formEditEvenement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
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
    
    fetch('{{ route("evenements.update", $evenement->id) }}', {
        method: 'PUT',
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
            // Rediriger vers la page de détails de l'événement
            window.location.href = '{{ route("evenements.show", $evenement->id) }}';
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
            alerteModerne.error('Erreur lors de la mise à jour de l\'événement');
        } else {
            alert('Erreur lors de la mise à jour de l\'événement');
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
