@extends('layouts.app-with-sidebar')

@section('title', 'Historique - ' . $membre->nom . ' ' . $membre->prenom)

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('membres.show', $membre) }}" class="mr-4 text-white/60 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white"><i class="fas fa-history mr-3"></i>Historique</h1>
                        <p class="text-white/70 mt-1">{{ $membre->prenom }} {{ $membre->nom }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.show', $membre) }}" 
                       class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-user mr-2"></i>Voir le profil
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Informations du membre -->
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 mb-8 border border-white/20">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    @if($membre->photo_url)
                    <img class="h-16 w-16 rounded-full" src="{{ $membre->photo_url }}" alt="{{ $membre->nom }}">
                    @else
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-white">{{ $membre->nom }} {{ $membre->prenom }}</h2>
                    <p class="text-white/70">{{ $membre->email }}</p>
                    <p class="text-white/60 text-sm">Membre depuis {{ $membre->date_adhesion->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Historique des actions -->
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg overflow-hidden border border-white/20">
            <div class="px-6 py-4 bg-white/5 border-b border-white/20">
                <h2 class="text-xl font-semibold text-white">Historique des Actions</h2>
                <p class="text-white/60 text-sm mt-1">Chronologie des modifications et actions effectuées</p>
            </div>
            
            <div class="p-6">
                @if($historique->count() > 0)
                <div class="space-y-4">
                    @foreach($historique as $action)
                    <div class="flex items-start space-x-4 p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors duration-200">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-{{ $action->couleur_action }}-500 to-{{ $action->couleur_action }}-600 flex items-center justify-center">
                                <i class="{{ $action->icone_action }} text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-white">{{ ucfirst(str_replace('_', ' ', $action->action)) }}</h3>
                                <time class="text-sm text-white/60">{{ $action->created_at->format('d/m/Y à H:i') }}</time>
                            </div>
                            <p class="text-white/70 mt-1">{{ $action->description }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-white/50">Par {{ $action->utilisateur }}</p>
                                @if($action->ip_address)
                                <p class="text-xs text-white/40">{{ $action->ip_address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-white/60">
                    <i class="fas fa-history text-4xl mb-4"></i>
                    <p class="text-lg">Aucun historique disponible</p>
                    <p class="text-sm">Les actions de ce membre apparaîtront ici</p>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
