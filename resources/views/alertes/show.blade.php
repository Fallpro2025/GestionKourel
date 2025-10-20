@extends('layouts.app-with-sidebar')

@section('title', 'Détails de l\'Alerte - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-bell mr-3"></i>{{ $alerte->getTitre() }}</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('alertes.index') }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('alertes.edit', $alerte->id) }}" 
                       class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    @if($alerte->statut !== 'resolu')
                    <button onclick="marquerCommeLue({{ $alerte->id }})"
                            class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-check mr-2"></i>Marquer comme lue
                    </button>
                    <button onclick="resoudreAlerte({{ $alerte->id }}, '{{ $alerte->getTitre() }}')"
                            class="px-3 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                        <i class="fas fa-check-double mr-2"></i>Résoudre
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Informations générales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Carte principale -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Informations de l'alerte</h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $alerte->statut === 'nouveau' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                               ($alerte->statut === 'envoye' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                               ($alerte->statut === 'lu' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')) }}">
                            {{ $alerte->statut_francais }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $alerte->niveau_urgence === 'info' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                               ($alerte->niveau_urgence === 'warning' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                               ($alerte->niveau_urgence === 'error' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30')) }}">
                            {{ $alerte->niveau_urgence_francais }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-white/70">Message</label>
                        <p class="text-white mt-1">{{ $alerte->message }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Type d'alerte</label>
                            <p class="text-white mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alerte->type === 'absence_repetitive' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 
                                       ($alerte->type === 'absence_non_justifiee' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($alerte->type === 'retard_excessif' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                                       ($alerte->type === 'cotisation_retard' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30'))) }}">
                                    {{ $alerte->type_francais }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Membre concerné</label>
                            <p class="text-white mt-1">{{ $alerte->membre ? $alerte->membre->nom . ' ' . $alerte->membre->prenom : 'Non spécifié' }}</p>
                        </div>
                    </div>

                    @if($alerte->activite)
                    <div>
                        <label class="text-sm font-medium text-white/70">Activité concernée</label>
                        <p class="text-white mt-1">
                            <a href="{{ route('activites.show', $alerte->activite->id) }}" 
                               class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                {{ $alerte->activite->nom }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($alerte->evenement)
                    <div>
                        <label class="text-sm font-medium text-white/70">Événement concerné</label>
                        <p class="text-white mt-1">
                            <a href="{{ route('evenements.show', $alerte->evenement->id) }}" 
                               class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                {{ $alerte->evenement->nom }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($alerte->canal_notification)
                    <div>
                        <label class="text-sm font-medium text-white/70">Canaux de notification</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($alerte->canal_notification as $canal)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                {{ ucfirst($canal) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations temporelles -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Informations temporelles</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Créée le</span>
                            <span class="font-medium text-white">{{ $alerte->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>

                    @if($alerte->sent_at)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Envoyée le</span>
                            <span class="font-medium text-white">{{ $alerte->sent_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($alerte->resolved_at)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Résolue le</span>
                            <span class="font-medium text-white">{{ $alerte->resolved_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($alerte->resolvedBy)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Résolue par</span>
                            <span class="font-medium text-white">{{ $alerte->resolvedBy->nom }} {{ $alerte->resolvedBy->prenom }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-white/20">
                        <div class="text-center">
                            <p class="text-sm text-white/70">Durée depuis création</p>
                            <p class="text-lg font-bold text-white">{{ $alerte->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        @if($alerte->statut !== 'resolu')
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
            <h3 class="text-lg font-semibold text-white mb-4">Actions rapides</h3>
            
            <div class="flex flex-wrap gap-4">
                @if($alerte->statut === 'nouveau' || $alerte->statut === 'envoye')
                <button onclick="marquerCommeLue({{ $alerte->id }})"
                        class="px-4 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                    <i class="fas fa-check mr-2"></i>Marquer comme lue
                </button>
                @endif
                
                <button onclick="resoudreAlerte({{ $alerte->id }}, '{{ $alerte->getTitre() }}')"
                        class="px-4 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                    <i class="fas fa-check-double mr-2"></i>Résoudre l'alerte
                </button>
                
                <button onclick="supprimerAlerte({{ $alerte->id }}, '{{ $alerte->getTitre() }}')"
                        class="px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
        @endif
    </main>
</div>
@endsection

@section('scripts')
<script>
// Fonction de marquage comme lue
function marquerCommeLue(id) {
    fetch(`/alertes/${id}/marquer-lue`, {
        method: 'POST',
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
            alerteModerne.error('Erreur lors du marquage');
        } else {
            alert('Erreur lors du marquage');
        }
    });
}

// Fonction de résolution
function resoudreAlerte(id, nom) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir résoudre l'alerte "${nom}" ?`,
            function(confirmed) {
                if (confirmed) {
                    resoudreAlerteConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir résoudre l'alerte "${nom}" ?`)) {
            resoudreAlerteConfirm(id);
        }
    }
}

function resoudreAlerteConfirm(id) {
    fetch(`/alertes/${id}/resoudre`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            resolved_by: 1 // TODO: Remplacer par l'ID de l'utilisateur connecté
        })
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
            alerteModerne.error('Erreur lors de la résolution');
        } else {
            alert('Erreur lors de la résolution');
        }
    });
}

// Fonction de suppression
function supprimerAlerte(id, nom) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne.confirmation) {
        alerteModerne.confirmation(
            `Êtes-vous sûr de vouloir supprimer l'alerte "${nom}" ?`,
            function(confirmed) {
                if (confirmed) {
                    supprimerAlerteConfirm(id);
                }
            }
        );
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'alerte "${nom}" ?`)) {
            supprimerAlerteConfirm(id);
        }
    }
}

function supprimerAlerteConfirm(id) {
    fetch(`/alertes/${id}`, {
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
            window.location.href = '{{ route("alertes.index") }}';
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
