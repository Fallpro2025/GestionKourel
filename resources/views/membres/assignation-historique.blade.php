@extends('layouts.app-with-sidebar')

@section('title', 'Historique des Paiements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-history mr-3 text-green-400"></i>
                        Historique des Paiements
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.assignations.show', [$membre, $assignation]) }}" 
                       class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour aux détails
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages d'alerte modernes -->
        @include('components.alertes-session')

        <!-- Informations de l'assignation -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $assignation->projet->nom }}</h2>
                    <p class="text-white/70">Assignation du {{ $assignation->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/70">Montant assigné</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($assignation->montant_assigné, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>

        <!-- Résumé des paiements -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Payé</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($assignation->montant_payé, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Montant Restant</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($assignation->montant_restant, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-orange-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Pourcentage</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($assignation->pourcentage_payé, 1) }}%</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-percentage text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des paiements -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-list mr-3 text-green-400"></i>
                    Historique des Paiements
                </h3>
            </div>

            @if(count($historiquePaiements) > 0)
                <div class="divide-y divide-white/10">
                    @foreach($historiquePaiements as $index => $paiement)
                    <div class="px-6 py-4 hover:bg-white/5 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-green-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium">
                                        Paiement #{{ $index + 1 }} - {{ number_format($paiement['montant'] ?? 0, 0, ',', ' ') }} FCFA
                                    </p>
                                    <p class="text-white/60 text-sm">
                                        {{ $paiement['methode'] ?? 'Non spécifié' }} - 
                                        {{ isset($paiement['date']) ? \Carbon\Carbon::parse($paiement['date'])->format('d/m/Y H:i') : 'Date inconnue' }}
                                    </p>
                                    @if(isset($paiement['notes']) && $paiement['notes'])
                                    <p class="text-white/50 text-xs mt-1">{{ $paiement['notes'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                    <i class="fas fa-check mr-1"></i>
                                    Payé
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-white/60">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Aucun paiement enregistré</p>
                        <p class="text-sm">Aucun paiement n'a encore été effectué pour cette assignation</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mt-8">
            <h3 class="text-lg font-semibold text-white mb-4">Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('membres.assignations.show', [$membre, $assignation]) }}" 
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Voir les détails de l'assignation
                </a>
                <a href="{{ route('membres.assignations.index', $membre) }}" 
                   class="px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux assignations
                </a>
            </div>
        </div>
    </main>
</div>
@endsection
