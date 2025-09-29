@extends('layouts.app-with-sidebar')

@section('title', 'Détails du Rôle')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-user-tag mr-3"></i>{{ $role->nom }}</h1>
                    <span class="ml-3 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        Niveau {{ $role->niveau_priorite }}
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('roles.index') }}" 
                       class="px-4 py-2 bg-gray-500/20 text-gray-300 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('roles.edit', $role) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-info-circle mr-2"></i>Description
                    </h2>
                    <p class="text-white/80 leading-relaxed">
                        {{ $role->description ?? 'Aucune description disponible pour ce rôle.' }}
                    </p>
                </div>

                <!-- Permissions -->
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-shield-alt mr-2"></i>Permissions
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse($role->permissions ?? [] as $permission)
                        <div class="flex items-center p-3 bg-green-500/20 rounded-lg border border-green-500/30">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <span class="text-green-300 font-medium">{{ ucfirst(str_replace('_', ' ', $permission)) }}</span>
                        </div>
                        @empty
                        <div class="col-span-2 text-center py-8">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl mb-2"></i>
                            <p class="text-white/60">Aucune permission spécifique</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Membres avec ce rôle -->
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-users mr-2"></i>Membres avec ce rôle
                    </h2>
                    <div class="space-y-3">
                        @forelse($role->membres as $membre)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-white">{{ $membre->nom }} {{ $membre->prenom }}</div>
                                    <div class="text-sm text-white/60">{{ $membre->email }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($membre->pivot->est_principal)
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full border border-yellow-500/30">
                                    Principal
                                </span>
                                @endif
                                <a href="{{ route('membres.show', $membre) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                            <p class="text-white/60">Aucun membre n'a ce rôle</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations générales -->
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-lg font-bold text-white mb-4">
                        <i class="fas fa-chart-bar mr-2"></i>Statistiques
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Niveau de priorité</span>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                                {{ $role->niveau_priorite }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Nombre de membres</span>
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 text-sm rounded-full border border-green-500/30">
                                {{ $role->membres->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Permissions</span>
                            <span class="px-2 py-1 bg-purple-500/20 text-purple-400 text-sm rounded-full border border-purple-500/30">
                                {{ count($role->permissions ?? []) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Créé le</span>
                            <span class="text-white/80 text-sm">{{ $role->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Modifié le</span>
                            <span class="text-white/80 text-sm">{{ $role->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-lg font-bold text-white mb-4">
                        <i class="fas fa-bolt mr-2"></i>Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('roles.edit', $role) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition-colors duration-200 border border-blue-500/30">
                            <i class="fas fa-edit mr-2"></i>Modifier le rôle
                        </a>
                        <button onclick="supprimerRole({{ $role->id }}, '{{ $role->nom }}')" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors duration-200 border border-red-500/30"
                                {{ $role->membres->count() > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash mr-2"></i>Supprimer le rôle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
// Supprimer un rôle
function supprimerRole(roleId, nomRole) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne) {
        alerteModerne.confirmation(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" ?`, function(confirme) {
            if (confirme) {
                supprimerRoleAction(roleId);
            }
        });
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" ?`)) {
            supprimerRoleAction(roleId);
        }
    }
}

function supprimerRoleAction(roleId) {
    fetch(`/roles/${roleId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            window.location.href = '/roles';
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
            alerteModerne.error('Erreur lors de la suppression');
        } else {
            alert('Erreur lors de la suppression');
        }
    });
}
</script>
@endsection