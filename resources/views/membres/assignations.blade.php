@extends('layouts.app-with-sidebar')

@section('title', 'Mes Assignations de Cotisation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-money-bill-wave mr-3 text-green-400"></i>
                        Mes Assignations de Cotisation
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.show', $membre) }}" 
                       class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au profil
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages d'alerte modernes -->
        @include('components.alertes-session')

        <!-- Informations du membre -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mb-8">
            <div class="flex items-center space-x-6">
                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    @if($membre->photo_url)
                        <img src="{{ asset('storage/' . $membre->photo_url) }}" 
                             alt="{{ $membre->nom }}" 
                             class="h-20 w-20 rounded-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-white">
                            {{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}
                        </span>
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-white">{{ $membre->nom }} {{ $membre->prenom }}</h2>
                    <p class="text-white/70">{{ $membre->matricule }}</p>
                    <p class="text-white/60">{{ $membre->email }}</p>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Assignations</p>
                        <p class="text-2xl font-bold text-white">{{ $statistiques['total_assignations'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-list text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Montant Total Assigné</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($statistiques['montant_total_assigné'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Montant Total Payé</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($statistiques['montant_total_payé'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-emerald-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Pourcentage Moyen</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($statistiques['pourcentage_moyen_payé'], 1) }}%</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                        <i class="fas fa-percentage text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des assignations -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-clipboard-list mr-3 text-blue-400"></i>
                    Mes Assignations
                </h3>
            </div>

            @if($assignations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Projet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Montant Assigné</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Montant Payé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Pourcentage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Échéance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($assignations as $assignation)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                            <i class="fas fa-project-diagram text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $assignation->projet->nom }}</div>
                                            <div class="text-sm text-white/60">{{ $assignation->projet->type_cotisation }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ number_format($assignation->montant_assigné, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ number_format($assignation->montant_payé, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-white/20 rounded-full h-2 mr-2">
                                            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full" 
                                                 style="width: {{ $assignation->pourcentage_payé }}%"></div>
                                        </div>
                                        <span class="text-sm text-white font-medium">{{ number_format($assignation->pourcentage_payé, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($assignation->statut_paiement === 'paye')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <i class="fas fa-check mr-1"></i>
                                            Payé
                                        </span>
                                    @elseif($assignation->en_retard)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            En retard
                                        </span>
                                    @elseif($assignation->statut_paiement === 'partiel')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                            <i class="fas fa-clock mr-1"></i>
                                            Partiel
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                            <i class="fas fa-hourglass-half mr-1"></i>
                                            En attente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($assignation->en_retard)
                                        <span class="text-red-400 font-medium">
                                            {{ $assignation->date_echeance->format('d/m/Y') }}
                                            <br>
                                            <small class="text-red-300">({{ $assignation->jours_retard }} jours de retard)</small>
                                        </span>
                                    @else
                                        <span class="text-white/70">{{ $assignation->date_echeance->format('d/m/Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('membres.assignations.show', [$membre, $assignation]) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('membres.assignations.historique', [$membre, $assignation]) }}" 
                                           class="text-green-400 hover:text-green-300 transition-colors duration-200"
                                           title="Historique des paiements">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-white/60">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Aucune assignation</p>
                        <p class="text-sm">Vous n'avez pas encore d'assignations de cotisation</p>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
