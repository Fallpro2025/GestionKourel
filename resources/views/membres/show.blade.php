@extends('layouts.app-with-sidebar')

@section('title', 'Détails du Membre - ' . $membre->nom . ' ' . $membre->prenom)

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('membres.index') }}" class="mr-4 text-white/60 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white"><i class="fas fa-user mr-3"></i>Détails du Membre</h1>
                        <p class="text-white/70 mt-1">{{ $membre->prenom }} {{ $membre->nom }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.export-pdf', $membre) }}" 
                       class="px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30">
                        <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
                    </a>
                    <a href="{{ route('membres.roles.index', $membre) }}" 
                       class="px-4 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30">
                        <i class="fas fa-user-tag mr-2"></i>Gérer les Rôles
                    </a>
                    <a href="{{ route('membres.edit', $membre) }}" 
                       class="px-4 py-2 bg-yellow-500/20 text-yellow-400 font-medium rounded-xl hover:bg-yellow-500/30 transition-all duration-300 border border-yellow-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Carte principale -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
                    <div class="flex items-start space-x-6">
                        <!-- Photo avec upload -->
                        <div class="flex-shrink-0">
                            @include('components.upload-photo', [
                                'membre' => $membre,
                                'currentPhoto' => $membre->photo_url ? Storage::url($membre->photo_url) : null
                            ])
                        </div>
                        
                        <!-- Informations de base -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-3xl font-bold text-white">{{ $membre->prenom }} {{ $membre->nom }}</h2>
                                    <p class="text-white/70 text-lg">{{ $membre->role->nom ?? 'Sans rôle' }}</p>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if($membre->statut === 'actif') bg-green-500/20 text-green-400 border border-green-500/30
                                    @elseif($membre->statut === 'inactif') bg-gray-500/20 text-gray-400 border border-gray-500/30
                                    @else bg-red-500/20 text-red-400 border border-red-500/30
                                    @endif">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    {{ ucfirst($membre->statut) }}
                                </span>
                            </div>
                            
                            <!-- Informations de contact -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center text-white/70">
                                    <i class="fas fa-envelope mr-3 text-blue-400"></i>
                                    <span>{{ $membre->email }}</span>
                                </div>
                                <div class="flex items-center text-white/70">
                                    <i class="fas fa-phone mr-3 text-green-400"></i>
                                    <span>{{ $membre->telephone }}</span>
                                </div>
                                <div class="flex items-center text-white/70">
                                    <i class="fas fa-calendar mr-3 text-purple-400"></i>
                                    <span>Membre depuis {{ $membre->date_adhesion->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center text-white/70">
                                    <i class="fas fa-id-card mr-3 text-orange-400"></i>
                                    <span>Matricule: {{ $membre->matricule ?? 'Non défini' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-400"></i>
                        Informations personnelles
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Date de naissance</label>
                            <p class="text-white">{{ $membre->date_naissance ? $membre->date_naissance->format('d/m/Y') : 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Profession</label>
                            <p class="text-white">{{ $membre->profession ?? 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Niveau d'étude</label>
                            <p class="text-white">{{ $membre->niveau_etude ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Adresse</label>
                            <p class="text-white">{{ $membre->adresse ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Compétences et disponibilités -->
                @if($membre->competences || $membre->disponibilites)
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-star mr-3 text-yellow-400"></i>
                        Compétences et disponibilités
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($membre->competences)
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Compétences</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($membre->competences as $competence)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    {{ $competence }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($membre->disponibilites)
                        <div>
                            <label class="block text-white/70 text-sm font-medium mb-2">Disponibilités</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($membre->disponibilites as $disponibilite)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-500/20 text-green-400 border border-green-500/30">
                                    {{ $disponibilite }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Rôles actuels -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-user-tag mr-3 text-purple-400"></i>
                        Rôles actuels
                    </h3>
                    
                    @if($membre->roles->count() > 0)
                    <div class="space-y-3">
                        @foreach($membre->roles as $role)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-xs">{{ substr($role->nom, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $role->nom }}</p>
                                    <p class="text-white/60 text-sm">Niveau {{ $role->niveau_priorite }}</p>
                                </div>
                            </div>
                            @if($role->pivot->est_principal)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                <i class="fas fa-crown mr-1"></i>Principal
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-white/60 text-center py-4">Aucun rôle attribué</p>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('membres.roles.index', $membre) }}" 
                           class="w-full px-4 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30 text-center block">
                            <i class="fas fa-plus mr-2"></i>Gérer les rôles
                        </a>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-bolt mr-3 text-yellow-400"></i>
                        Actions rapides
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('membres.edit', $membre) }}" 
                           class="w-full px-4 py-3 bg-yellow-500/20 text-yellow-400 font-medium rounded-xl hover:bg-yellow-500/30 transition-all duration-300 border border-yellow-500/30 text-center block">
                            <i class="fas fa-edit mr-2"></i>Modifier le membre
                        </a>
                        
                        <a href="{{ route('membres.roles.index', $membre) }}" 
                           class="w-full px-4 py-3 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30 text-center block">
                            <i class="fas fa-user-tag mr-2"></i>Gérer les rôles
                        </a>
                        
                        <a href="{{ route('membres.historique', $membre) }}" 
                           class="w-full px-4 py-3 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30 text-center block">
                            <i class="fas fa-history mr-2"></i>Voir l'historique
                        </a>
                        
                        <a href="{{ route('membres.assignations.index', $membre) }}" 
                           class="w-full px-4 py-3 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30 text-center block">
                            <i class="fas fa-money-bill-wave mr-2"></i>Mes assignations
                        </a>
                        
                        <a href="{{ route('membres.export-pdf', $membre) }}" 
                           class="w-full px-4 py-3 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30 text-center block">
                            <i class="fas fa-file-pdf mr-2"></i>Exporter en PDF
                        </a>
                        
                        <form action="{{ route('membres.destroy', $membre) }}" method="POST" 
                              onsubmit="return confirmerSuppression(event, '{{ $membre->nom }} {{ $membre->prenom }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30">
                                <i class="fas fa-trash mr-2"></i>Supprimer le membre
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-green-400"></i>
                        Statistiques
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Nombre de rôles</span>
                            <span class="text-white font-bold">{{ $membre->roles->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Membre depuis</span>
                            <span class="text-white font-bold">{{ $membre->date_adhesion->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Statut</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($membre->statut === 'actif') bg-green-500/20 text-green-400 border border-green-500/30
                                @elseif($membre->statut === 'inactif') bg-gray-500/20 text-gray-400 border border-gray-500/30
                                @else bg-red-500/20 text-red-400 border border-red-500/30
                                @endif">
                                {{ ucfirst($membre->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Fonction pour confirmer la suppression d'un membre
function confirmerSuppression(event, nomMembre) {
    event.preventDefault();
    
    alerteModerne.confirmation(
        `Êtes-vous sûr de vouloir supprimer le membre "${nomMembre}" ?`,
        function(confirme) {
            if (confirme) {
                // Soumettre le formulaire
                event.target.submit();
            }
        }
    );
    
    return false;
}
</script>
@endsection
