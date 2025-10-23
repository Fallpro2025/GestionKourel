@extends('layouts.app-with-sidebar')

@section('title', 'Détails de l\'Événement - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-star mr-3"></i>{{ $evenement->nom }}</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('evenements.index') }}" 
                       class="px-3 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                    <a href="{{ route('evenements.edit', $evenement->id) }}" 
                       class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <a href="{{ route('evenements.participants', $evenement->id) }}" 
                       class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30">
                        <i class="fas fa-users mr-2"></i>Gérer les participants
                    </a>
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
                    <h2 class="text-xl font-bold text-white">Informations de l'événement</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $evenement->statut === 'planifie' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 
                           ($evenement->statut === 'confirme' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                           ($evenement->statut === 'en_cours' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                           ($evenement->statut === 'termine' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'))) }}">
                        {{ $evenement->statut_francais }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-white/70">Description</label>
                        <p class="text-white mt-1">{{ $evenement->description ?: 'Aucune description' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Type d'événement</label>
                            <p class="text-white mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $evenement->type === 'magal' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                       ($evenement->type === 'gamou' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                       ($evenement->type === 'promokhane' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 
                                       ($evenement->type === 'conference' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                                       ($evenement->type === 'formation' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30')))) }}">
                                    {{ $evenement->type_francais }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Créé par</label>
                            <p class="text-white mt-1">{{ $evenement->createur ? $evenement->createur->nom . ' ' . $evenement->createur->prenom : 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Date de début</label>
                            <p class="text-white mt-1">{{ $evenement->date_debut->format('d/m/Y à H:i') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Date de fin</label>
                            <p class="text-white mt-1">{{ $evenement->date_fin->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-white/70">Lieu</label>
                            <p class="text-white mt-1">{{ $evenement->lieu ?: 'Non spécifié' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-white/70">Budget</label>
                            <p class="text-white mt-1">{{ $evenement->budget ? number_format($evenement->budget, 0, ',', ' ') . ' FCFA' : 'Non défini' }}</p>
                        </div>
                    </div>

                    @if($evenement->configuration)
                    <div>
                        <label class="text-sm font-medium text-white/70">Configuration</label>
                        <div class="mt-2 p-3 bg-white/5 rounded-lg">
                            <pre class="text-white text-sm">{{ json_encode($evenement->configuration, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Durée</span>
                            <span class="font-medium text-white">{{ $stats['duree_heures'] }}h</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Nombre de prestations</span>
                            <span class="font-medium text-white">{{ $stats['nombre_prestations'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Total participants</span>
                            <span class="font-medium text-green-400">{{ $stats['nombre_total_membres'] }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/70">Budget</span>
                            <span class="font-medium text-purple-400">{{ $stats['budget'] ? number_format($stats['budget'], 0, ',', ' ') . ' FCFA' : 'Non défini' }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/20">
                        <div class="text-center">
                            <p class="text-sm text-white/70">Type</p>
                            <p class="text-lg font-bold text-white">{{ $stats['type'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des participants par prestation -->
        @if($membresSelectionnes && count($membresSelectionnes) > 0)
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white">Participants par prestation</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($membresSelectionnes as $prestation => $membres)
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <h4 class="text-lg font-semibold text-white mb-3 capitalize">
                            {{ str_replace('_', ' ', $prestation) }} ({{ count($membres) }})
                        </h4>
                        
                        <div class="space-y-2">
                            @foreach($membres as $membre)
                            <div class="flex items-center space-x-3 p-2 bg-white/5 rounded-lg">
                                <div class="flex-shrink-0 h-8 w-8">
                                    @if($membre->photo_url)
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ asset('storage/' . $membre->photo_url) }}" 
                                         alt="{{ $membre->nom }}">
                                    @else
                                    <div class="h-8 w-8 rounded-full bg-gray-500/20 flex items-center justify-center">
                                        <i class="fas fa-user text-white/60 text-xs"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $membre->nom }} {{ $membre->prenom }}</p>
                                    <p class="text-xs text-white/60 truncate">{{ $membre->email }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
            <div class="px-6 py-12 text-center">
                <div class="text-white/60">
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <p class="text-lg">Aucun participant assigné</p>
                    <p class="text-sm">Les participants seront affichés ici une fois assignés</p>
                </div>
            </div>
        </div>
        @endif
    </main>
</div>
@endsection

@section('scripts')
<script>
// Scripts spécifiques à la page de détails
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips et autres interactions
    console.log('Page de détails de l\'événement chargée');
});
</script>
@endsection
