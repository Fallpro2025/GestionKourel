@extends('layouts.app-with-sidebar')

@section('title', 'Modifier l\'Alerte - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-edit mr-3"></i>Modifier l'Alerte</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('alertes.show', $alerte->id) }}" 
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
            <form id="formEditAlerte" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Type et Niveau d'urgence -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-white/80">Type d'alerte <span class="text-red-400">*</span></label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner un type...</option>
                            <option value="absence_repetitive" class="text-gray-800" {{ $alerte->type === 'absence_repetitive' ? 'selected' : '' }}>Absence répétitive</option>
                            <option value="absence_non_justifiee" class="text-gray-800" {{ $alerte->type === 'absence_non_justifiee' ? 'selected' : '' }}>Absence non justifiée</option>
                            <option value="retard_excessif" class="text-gray-800" {{ $alerte->type === 'retard_excessif' ? 'selected' : '' }}>Retard excessif</option>
                            <option value="cotisation_retard" class="text-gray-800" {{ $alerte->type === 'cotisation_retard' ? 'selected' : '' }}>Cotisation en retard</option>
                            <option value="evenement_majeur" class="text-gray-800" {{ $alerte->type === 'evenement_majeur' ? 'selected' : '' }}>Événement majeur</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="niveau_urgence" class="block text-sm font-semibold text-white/80">Niveau d'urgence <span class="text-red-400">*</span></label>
                        <select id="niveau_urgence" name="niveau_urgence" required
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="info" class="text-gray-800" {{ $alerte->niveau_urgence === 'info' ? 'selected' : '' }}>Information</option>
                            <option value="warning" class="text-gray-800" {{ $alerte->niveau_urgence === 'warning' ? 'selected' : '' }}>Attention</option>
                            <option value="error" class="text-gray-800" {{ $alerte->niveau_urgence === 'error' ? 'selected' : '' }}>Erreur</option>
                            <option value="critical" class="text-gray-800" {{ $alerte->niveau_urgence === 'critical' ? 'selected' : '' }}>Critique</option>
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
                            @foreach($membres as $membre)
                            <option value="{{ $membre->id }}" class="text-gray-800" {{ $alerte->membre_id == $membre->id ? 'selected' : '' }}>
                                {{ $membre->nom }} {{ $membre->prenom }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="activite_id" class="block text-sm font-semibold text-white/80">Activité concernée</label>
                        <select id="activite_id" name="activite_id"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Sélectionner une activité...</option>
                            @foreach($activites as $activite)
                            <option value="{{ $activite->id }}" class="text-gray-800" {{ $alerte->activite_id == $activite->id ? 'selected' : '' }}>
                                {{ $activite->nom }}
                            </option>
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
                        @foreach($evenements as $evenement)
                        <option value="{{ $evenement->id }}" class="text-gray-800" {{ $alerte->evenement_id == $evenement->id ? 'selected' : '' }}>
                            {{ $evenement->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-semibold text-white/80">Message <span class="text-red-400">*</span></label>
                    <textarea id="message" name="message" rows="4" required
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50"
                              placeholder="Message de l'alerte">{{ $alerte->message }}</textarea>
                </div>

                <!-- Statut -->
                <div class="space-y-2">
                    <label for="statut" class="block text-sm font-semibold text-white/80">Statut <span class="text-red-400">*</span></label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white">
                        <option value="nouveau" class="text-gray-800" {{ $alerte->statut === 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                        <option value="envoye" class="text-gray-800" {{ $alerte->statut === 'envoye' ? 'selected' : '' }}>Envoyé</option>
                        <option value="lu" class="text-gray-800" {{ $alerte->statut === 'lu' ? 'selected' : '' }}>Lu</option>
                        <option value="resolu" class="text-gray-800" {{ $alerte->statut === 'resolu' ? 'selected' : '' }}>Résolu</option>
                    </select>
                </div>

                <!-- Canaux de notification -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-white/80">Canaux de notification</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="email" 
                                   {{ $alerte->canal_notification && in_array('email', $alerte->canal_notification) ? 'checked' : '' }}
                                   class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">Email</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="sms" 
                                   {{ $alerte->canal_notification && in_array('sms', $alerte->canal_notification) ? 'checked' : '' }}
                                   class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">SMS</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="whatsapp" 
                                   {{ $alerte->canal_notification && in_array('whatsapp', $alerte->canal_notification) ? 'checked' : '' }}
                                   class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">WhatsApp</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="canal_notification[]" value="push" 
                                   {{ $alerte->canal_notification && in_array('push', $alerte->canal_notification) ? 'checked' : '' }}
                                   class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-500">
                            <span class="text-white text-sm">Push</span>
                        </label>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('alertes.show', $alerte->id) }}"
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
document.getElementById('formEditAlerte').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("alertes.update", $alerte->id) }}', {
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
            // Rediriger vers la page de détails de l'alerte
            window.location.href = '{{ route("alertes.show", $alerte->id) }}';
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
            alerteModerne.error('Erreur lors de la mise à jour de l\'alerte');
        } else {
            alert('Erreur lors de la mise à jour de l\'alerte');
        }
    });
});
</script>
@endsection
