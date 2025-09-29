@extends('layouts.app-with-sidebar')

@section('title', 'Détails de l\'Assignation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-400"></i>
                        Détails de l'Assignation
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.assignations.index', $membre) }}" 
                       class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour aux assignations
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages d'alerte modernes -->
        @include('components.alertes-session')

        <!-- Informations générales -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-project-diagram mr-3 text-blue-400"></i>
                    {{ $assignation->projet->nom }}
                </h2>
                @if($assignation->statut_paiement === 'paye')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                        <i class="fas fa-check mr-2"></i>
                        Payé
                    </span>
                @elseif($assignation->en_retard)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        En retard
                    </span>
                @elseif($assignation->statut_paiement === 'partiel')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                        <i class="fas fa-clock mr-2"></i>
                        Partiel
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        En attente
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Informations du Projet</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-white/70">Type de cotisation:</span>
                            <span class="text-white font-medium">{{ ucfirst($assignation->projet->type_cotisation) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/70">Date de début:</span>
                            <span class="text-white font-medium">{{ $assignation->projet->date_debut->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/70">Date de fin:</span>
                            <span class="text-white font-medium">{{ $assignation->projet->date_fin->format('d/m/Y') }}</span>
                        </div>
                        @if($assignation->projet->description)
                        <div>
                            <span class="text-white/70">Description:</span>
                            <p class="text-white mt-1">{{ $assignation->projet->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Détails de l'Assignation</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-white/70">Date d'assignation:</span>
                            <span class="text-white font-medium">{{ $assignation->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/70">Date d'échéance:</span>
                            <span class="text-white font-medium {{ $assignation->en_retard ? 'text-red-400' : '' }}">
                                {{ $assignation->date_echeance->format('d/m/Y') }}
                                @if($assignation->en_retard)
                                    <br><small class="text-red-300">({{ $assignation->jours_retard }} jours de retard)</small>
                                @endif
                            </span>
                        </div>
                        @if($assignation->date_dernier_paiement)
                        <div class="flex justify-between">
                            <span class="text-white/70">Dernier paiement:</span>
                            <span class="text-white font-medium">{{ $assignation->date_dernier_paiement->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Montants et pourcentage -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Montant Assigné</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($assignation->montant_assigné, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Montant Payé</p>
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
        </div>

        <!-- Barre de progression -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Progression du Paiement</h3>
                <span class="text-2xl font-bold text-white">{{ number_format($assignation->pourcentage_payé, 1) }}%</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-4">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $assignation->pourcentage_payé }}%"></div>
            </div>
            <div class="flex justify-between text-sm text-white/60 mt-2">
                <span>0 FCFA</span>
                <span>{{ number_format($assignation->montant_assigné, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('membres.assignations.historique', [$membre, $assignation]) }}" 
                   class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Voir l'historique des paiements
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
