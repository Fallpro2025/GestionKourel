@extends('layouts.app-with-sidebar')

@section('title', 'Modifier le Rôle')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-edit mr-3"></i>Modifier le Rôle</h1>
                    <span class="ml-3 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        {{ $role->nom }}
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('roles.show', $role) }}" 
                       class="px-4 py-2 bg-gray-500/20 text-gray-300 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300">
                        <i class="fas fa-eye mr-2"></i>Voir
                    </a>
                    <a href="{{ route('roles.index') }}" 
                       class="px-4 py-2 bg-gray-500/20 text-gray-300 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-8 border border-white/20">
            <form id="formRole" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Informations de base -->
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-info-circle mr-2"></i>Informations de base
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-800 mb-2">Nom du rôle *</label>
                            <input type="text" id="nom" name="nom" value="{{ $role->nom }}" required 
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200"
                                   placeholder="Ex: Administrateur, Membre, Trésorier...">
                        </div>
                        
                        <div>
                            <label for="niveau_priorite" class="block text-sm font-semibold text-gray-800 mb-2">Niveau de priorité *</label>
                            <select id="niveau_priorite" name="niveau_priorite" required
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200">
                                <option value="1" {{ $role->niveau_priorite == 1 ? 'selected' : '' }}>1 - Bas (Membre standard)</option>
                                <option value="2" {{ $role->niveau_priorite == 2 ? 'selected' : '' }}>2 - Moyen (Responsable section)</option>
                                <option value="3" {{ $role->niveau_priorite == 3 ? 'selected' : '' }}>3 - Élevé (Coordinateur)</option>
                                <option value="4" {{ $role->niveau_priorite == 4 ? 'selected' : '' }}>4 - Très élevé (Direction)</option>
                                <option value="5" {{ $role->niveau_priorite == 5 ? 'selected' : '' }}>5 - Administrateur (Accès complet)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200"
                                  placeholder="Décrivez les responsabilités de ce rôle...">{{ $role->description }}</textarea>
                    </div>
                </div>
                
                <!-- Permissions -->
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-shield-alt mr-2"></i>Permissions
                    </h2>
                    
                    <div class="bg-blue-50/80 rounded-xl p-6 border border-blue-200/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-80 overflow-y-auto" id="permissionsContainer">
                            @php
                                $permissionsDisponibles = [
                                    'voir_profil' => 'Voir les profils des membres',
                                    'gestion_membres' => 'Gérer les membres',
                                    'gestion_cotisations' => 'Gérer les cotisations',
                                    'gestion_evenements' => 'Gérer les événements',
                                    'gestion_finances' => 'Gérer les finances',
                                    'gestion_documents' => 'Gérer les documents',
                                    'gestion_activites' => 'Gérer les activités',
                                    'animer_activites' => 'Animer les activités',
                                    'participer_chorale' => 'Participer à la chorale',
                                    'participation_repetitions' => 'Participer aux répétitions',
                                    'participation_concerts' => 'Participer aux concerts',
                                    'interpretation_solos' => 'Interpréter des solos',
                                    'interpretation_instrumentale' => 'Interpréter des instruments',
                                    'gestion_section' => 'Gérer une section',
                                    'coordination_activites' => 'Coordonner les activités',
                                    'gestion_technique' => 'Gérer la technique',
                                    'maintenance_equipements' => 'Maintenir les équipements',
                                    'administration_generale' => 'Administration générale'
                                ];
                                $permissionsActuelles = $role->permissions ?? [];
                            @endphp
                            
                            @foreach($permissionsDisponibles as $key => $label)
                            <div class="flex items-center p-3 bg-white/50 rounded-lg hover:bg-white/70 transition-colors duration-200">
                                <input type="checkbox" id="perm_{{ $key }}" name="permissions[]" value="{{ $key }}" 
                                       {{ in_array($key, $permissionsActuelles) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_{{ $key }}" class="ml-3 text-sm text-gray-800 font-medium cursor-pointer">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('roles.show', $role) }}" 
                       class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
// Gestion du formulaire
document.getElementById('formRole').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const permissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked')).map(cb => cb.value);
    
    const data = {
        nom: formData.get('nom'),
        description: formData.get('description'),
        niveau_priorite: parseInt(formData.get('niveau_priorite')),
        permissions: permissions
    };
    
    fetch('{{ route("roles.update", $role) }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            window.location.href = '{{ route("roles.show", $role) }}';
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne) {
            alerteModerne.error('Erreur lors de l\'enregistrement');
        } else {
            alert('Erreur lors de l\'enregistrement');
        }
    });
});
</script>
@endsection