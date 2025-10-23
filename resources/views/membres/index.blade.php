@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Membres - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-users mr-3"></i>Gestion des Membres</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Vues -->
                    <div class="flex items-center space-x-2">
                        <button onclick="switchToGridView()" 
                                class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30"
                                title="Vue grille">
                            <i class="fas fa-th"></i>
                        </button>
                        <button onclick="switchToListView()" 
                                class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30"
                                title="Vue liste">
                            <i class="fas fa-list"></i>
                        </button>
                        <button onclick="switchToTableView()" 
                                class="px-3 py-2 bg-purple-500/20 text-purple-400 font-medium rounded-xl hover:bg-purple-500/30 transition-all duration-300 border border-purple-500/30"
                                title="Vue tableau">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('test-alertes-simple') }}" 
                           class="px-3 py-2 bg-yellow-500/20 text-yellow-400 font-medium rounded-xl hover:bg-yellow-500/30 transition-all duration-300 border border-yellow-500/30"
                           title="Test des alertes">
                            <i class="fas fa-flask"></i>
                        </a>
                        <a href="{{ route('membres.statistiques') }}" 
                           class="px-3 py-2 bg-indigo-500/20 text-indigo-400 font-medium rounded-xl hover:bg-indigo-500/30 transition-all duration-300 border border-indigo-500/30"
                           title="Voir les statistiques">
                            <i class="fas fa-chart-bar"></i>
                        </a>
                        <button onclick="exportMembres('pdf')" 
                                class="px-3 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30"
                                title="Exporter en PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button onclick="exportMembres('excel')" 
                                class="px-3 py-2 bg-green-500/20 text-green-400 font-medium rounded-xl hover:bg-green-500/30 transition-all duration-300 border border-green-500/30"
                                title="Exporter en Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button onclick="exportMembres('csv')" 
                                class="px-3 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30"
                                title="Exporter en CSV">
                            <i class="fas fa-file-csv"></i>
                        </button>
               <a href="{{ route('membres.import') }}" 
                  class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-purple-500/25">
                   <i class="fas fa-file-import mr-2"></i>Import Excel
               </a>
               <a href="{{ route('membres.create') }}" 
                  class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-green-500/25">
                   <i class="fas fa-plus mr-2"></i>Nouveau Membre
               </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Recherche avancée -->
        @include('components.recherche-avancee', ['roles' => $roles])
        
        <!-- Statistiques avancées -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total membres -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total Membres</p>
                        <p class="text-white text-2xl font-bold">{{ $membres->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-400 font-medium">+12%</span>
                    <span class="text-white/60 ml-2">vs mois dernier</span>
                </div>
            </div>

            <!-- Membres actifs -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Membres Actifs</p>
                        <p class="text-white text-2xl font-bold">{{ $membres->where('statut', 'actif')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-check text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-400 font-medium">{{ round(($membres->where('statut', 'actif')->count() / max($membres->count(), 1)) * 100) }}%</span>
                    <span class="text-white/60 ml-2">du total</span>
                </div>
            </div>

            <!-- Nouveaux ce mois -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Nouveaux ce mois</p>
                        <p class="text-white text-2xl font-bold">{{ $membres->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-purple-400 font-medium">+{{ $membres->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                    <span class="text-white/60 ml-2">ce mois</span>
                </div>
            </div>

            <!-- Taux d'engagement -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Taux d'Engagement</p>
                        <p class="text-white text-2xl font-bold">87%</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-yellow-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-yellow-400 font-medium">+5%</span>
                    <span class="text-white/60 ml-2">vs mois dernier</span>
                </div>
            </div>
        </div>
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')

        <!-- Barre de recherche et filtres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Rechercher un membre..." 
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                </div>
                <div class="flex gap-4">
                    <select id="filterRole" class="px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="" style="background: #374151; color: white;">Tous les rôles</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role->id }}" style="background: #374151; color: white;">{{ $role->nom }}</option>
                        @endforeach
                    </select>
                    <select id="filterStatut" class="px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="" style="background: #374151; color: white;">Tous les statuts</option>
                        <option value="actif" style="background: #374151; color: white;">Actif</option>
                        <option value="inactif" style="background: #374151; color: white;">Inactif</option>
                        <option value="suspendu" style="background: #374151; color: white;">Suspendu</option>
                    </select>
                    <button onclick="effacerFiltres()" 
                            class="px-4 py-3 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-300 border border-red-500/30 flex items-center space-x-2"
                            title="Effacer tous les filtres">
                        <i class="fas fa-times"></i>
                        <span>Effacer</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Vue Grille -->
        <div id="vueGrille" class="vue-container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($membres as $membre)
                <div class="member-card bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center overflow-hidden">
                            @if($membre->photo_url)
                                <img src="{{ asset('storage/' . $membre->photo_url) }}" 
                                     alt="{{ $membre->nom }}" 
                                     class="w-12 h-12 rounded-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            @endif
                            <span class="text-white font-bold text-lg" style="display: {{ $membre->photo_url ? 'none' : 'flex' }};">
                                {{ strtoupper(substr($membre->prenom ?? 'M', 0, 1)) }}{{ strtoupper(substr($membre->nom ?? 'M', 0, 1)) }}
                            </span>
                        </div>
                        <div class="status-badge px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                            {{ ucfirst($membre->statut) }}
                        </div>
                    </div>
                    
                    <h3 class="text-white font-bold text-lg mb-1">{{ $membre->prenom }} {{ $membre->nom }}</h3>
                    <p class="text-white/70 text-sm mb-2">{{ $membre->role->nom ?? 'Sans rôle' }}</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs">Email</span>
                            <span class="text-blue-400 font-semibold text-sm">{{ $membre->email }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs">Téléphone</span>
                            <span class="text-green-400 font-semibold text-sm">{{ $membre->telephone }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs">Inscrit le</span>
                            <span class="text-purple-400 font-semibold text-sm">{{ $membre->date_adhesion->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('membres.show', $membre) }}" 
                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                           title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('membres.export-pdf', $membre) }}" 
                           class="text-red-400 hover:text-red-300 transition-colors duration-200"
                           title="Exporter en PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('membres.roles.index', $membre) }}" 
                           class="text-purple-400 hover:text-purple-300 transition-colors duration-200"
                           title="Gérer les rôles">
                            <i class="fas fa-user-tag"></i>
                        </a>
                        <a href="{{ route('membres.edit', $membre) }}" 
                           class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                           title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('membres.destroy', $membre) }}" method="POST" 
                              onsubmit="return confirmerSuppression(event, '{{ $membre->nom }} {{ $membre->prenom }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-8">
                        <i class="fas fa-users text-white/40 text-6xl mb-4"></i>
                        <h3 class="text-white text-xl font-semibold mb-2">Aucun membre trouvé</h3>
                        <p class="text-white/60 mb-6">Commencez par ajouter votre premier membre au groupe</p>
                        <a href="{{ route('membres.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter le premier membre
                        </a>
                    </div>
                </div>
            @endforelse
            </div>
        </div>

        <!-- Vue Liste -->
        <div id="vueListe" class="vue-container hidden">
            <div class="space-y-4">
                @forelse($membres as $membre)
                <div class="member-list-item bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                    <div class="flex items-center space-x-6">
                        <!-- Photo -->
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                            @if($membre->photo_url)
                                <img src="{{ Storage::url($membre->photo_url) }}" 
                                     alt="{{ $membre->nom }}" 
                                     class="w-20 h-20 rounded-full object-cover">
                            @else
                                <span class="text-white font-bold text-2xl">
                                    {{ strtoupper(substr($membre->prenom ?? 'M', 0, 1)) }}{{ strtoupper(substr($membre->nom ?? 'M', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Informations principales -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="text-white font-bold text-xl">{{ $membre->prenom }} {{ $membre->nom }}</h3>
                                    <p class="text-white/70 text-sm">{{ $membre->role->nom ?? 'Sans rôle' }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($membre->statut === 'actif') bg-green-500/20 text-green-400 border border-green-500/30
                                        @elseif($membre->statut === 'inactif') bg-gray-500/20 text-gray-400 border border-gray-500/30
                                        @else bg-red-500/20 text-red-400 border border-red-500/30
                                        @endif">
                                        {{ ucfirst($membre->statut) }}
                                    </span>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('membres.show', $membre) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                           title="Voir détails">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                        <a href="{{ route('membres.roles.index', $membre) }}" 
                                           class="text-purple-400 hover:text-purple-300 transition-colors duration-200"
                                           title="Gérer les rôles">
                                            <i class="fas fa-user-tag text-lg"></i>
                                        </a>
                                        <a href="{{ route('membres.edit', $membre) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                           title="Modifier">
                                            <i class="fas fa-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('membres.destroy', $membre) }}" method="POST" 
                                              onsubmit="return confirmerSuppression(event, '{{ $membre->nom }} {{ $membre->prenom }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                                    title="Supprimer">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations détaillées -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white/60 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-3 text-blue-400"></i>
                                    <span class="truncate">{{ $membre->email }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone mr-3 text-green-400"></i>
                                    <span>{{ $membre->telephone }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-3 text-purple-400"></i>
                                    <span>Membre depuis {{ $membre->date_adhesion->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-white/60">
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <p class="text-lg">Aucun membre trouvé</p>
                    <p class="text-sm">Commencez par ajouter des membres à votre organisation</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Vue Tableau -->
        <div id="vueTableau" class="vue-container hidden">
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg overflow-hidden border border-white/20">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Photo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Téléphone</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($membres as $membre)
                            <tr class="member-table-row hover:bg-white/5 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                        @if($membre->photo_url)
                                            <img src="{{ Storage::url($membre->photo_url) }}" 
                                                 alt="{{ $membre->nom }}" 
                                                 class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($membre->prenom ?? 'M', 0, 1)) }}{{ strtoupper(substr($membre->nom ?? 'M', 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-white font-medium">{{ $membre->prenom }} {{ $membre->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-white/70">{{ $membre->role->nom ?? 'Sans rôle' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-white/70 text-sm">{{ $membre->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-white/70 text-sm">{{ $membre->telephone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($membre->statut === 'actif') bg-green-500/20 text-green-400 border border-green-500/30
                                        @elseif($membre->statut === 'inactif') bg-gray-500/20 text-gray-400 border border-gray-500/30
                                        @else bg-red-500/20 text-red-400 border border-red-500/30
                                        @endif">
                                        {{ ucfirst($membre->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('membres.show', $membre) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('membres.export-pdf', $membre) }}" 
                                           class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                           title="Exporter en PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('membres.roles.index', $membre) }}" 
                                           class="text-purple-400 hover:text-purple-300 transition-colors duration-200"
                                           title="Gérer les rôles">
                                            <i class="fas fa-user-tag"></i>
                                        </a>
                                        <a href="{{ route('membres.edit', $membre) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('membres.destroy', $membre) }}" method="POST" 
                                              onsubmit="return confirmerSuppression(event, '{{ $membre->nom }} {{ $membre->prenom }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun membre trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Variable globale pour la vue actuelle
let vueActuelle = localStorage.getItem('vueActuelle') || 'grille';

// Fonction pour changer la vue directement
function changerVueDirectement() {
    const vues = ['grille', 'liste', 'tableau'];
    const indexActuel = vues.indexOf(vueActuelle);
    const prochainIndex = (indexActuel + 1) % vues.length;
    vueActuelle = vues[prochainIndex];
    
    // Sauvegarder la vue dans localStorage
    localStorage.setItem('vueActuelle', vueActuelle);
    
    // Mettre à jour l'icône et le texte
    mettreAJourBoutonVue();
    
    // Appeler la fonction JavaScript existante
    if (vueActuelle === 'grille') {
        switchToGridView();
    } else if (vueActuelle === 'liste') {
        switchToListView();
    } else if (vueActuelle === 'tableau') {
        switchToTableView();
    }
}

// Fonction pour mettre à jour le bouton
function mettreAJourBoutonVue() {
    const icone = document.getElementById('vueIcon');
    const texte = document.getElementById('vueTexte');
    
    const icones = {
        'grille': 'fas fa-th',
        'liste': 'fas fa-list',
        'tableau': 'fas fa-table'
    };
    
    const textes = {
        'grille': 'Grille',
        'liste': 'Liste',
        'tableau': 'Tableau'
    };
    
    if (icone) {
        icone.className = icones[vueActuelle] + ' w-5 h-5';
    }
    
    if (texte) {
        texte.textContent = textes[vueActuelle];
    }
}

// Fonctions de changement de vue simplifiées
function switchToGridView() {
    // Masquer toutes les vues
    document.querySelectorAll('.vue-container').forEach(container => {
        container.classList.add('hidden');
    });
    
    // Afficher la vue grille
    document.getElementById('vueGrille').classList.remove('hidden');
    
    vueActuelle = 'grille';
    mettreAJourBoutonVue();
}

function switchToListView() {
    // Masquer toutes les vues
    document.querySelectorAll('.vue-container').forEach(container => {
        container.classList.add('hidden');
    });
    
    // Afficher la vue liste
    document.getElementById('vueListe').classList.remove('hidden');
    
    vueActuelle = 'liste';
    mettreAJourBoutonVue();
}

function switchToTableView() {
    // Masquer toutes les vues
    document.querySelectorAll('.vue-container').forEach(container => {
        container.classList.add('hidden');
    });
    
    // Afficher la vue tableau
    document.getElementById('vueTableau').classList.remove('hidden');
    
    vueActuelle = 'tableau';
    mettreAJourBoutonVue();
}

// Initialiser la vue au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    mettreAJourBoutonVue();
    
    if (vueActuelle !== 'grille') {
        if (vueActuelle === 'liste') {
            switchToListView();
        } else if (vueActuelle === 'tableau') {
            switchToTableView();
        }
    }
});

// Fonction d'export des membres
function exportMembres(format) {
    if (format === 'csv') {
        window.location.href = '{{ route("membres.export.csv") }}';
        showNotification('Export CSV en cours...', 'info');
    } else if (format === 'excel') {
        window.location.href = '{{ route("membres.export.excel") }}';
        showNotification('Export Excel en cours...', 'info');
    } else if (format === 'pdf') {
        window.location.href = '{{ route("membres.export.pdf") }}';
        showNotification('Export PDF en cours...', 'info');
    }
}

// Fonction pour effacer tous les filtres
function effacerFiltres() {
    // Réinitialiser la barre de recherche
    document.getElementById('searchInput').value = '';
    
    // Réinitialiser les filtres
    document.getElementById('filterRole').value = '';
    document.getElementById('filterStatut').value = '';
    
    // Réafficher tous les membres dans toutes les vues
    const membresGrille = document.querySelectorAll('.member-card');
    const membresListe = document.querySelectorAll('.member-list-item');
    const membresTableau = document.querySelectorAll('.member-table-row');
    
    const tousLesMembres = [...membresGrille, ...membresListe, ...membresTableau];
    tousLesMembres.forEach(membre => {
        membre.style.display = '';
    });
}

// Fonction pour actualiser les statistiques après filtrage
function actualiserStatistiques() {
    const membresVisibles = document.querySelectorAll('.member-card:not([style*="display: none"]), .member-list-item:not([style*="display: none"]), .member-table-row:not([style*="display: none"])');
    
    // Mettre à jour les compteurs dans les statistiques si nécessaire
    console.log('Membres visibles:', membresVisibles.length);
}

// Fonction pour afficher les notifications modernes avec auto-disparition
function showNotification(message, type = 'info') {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notif => notif.remove());
    
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 transform transition-all duration-500 ease-in-out`;
    
    // Styles selon le type
    const styles = {
        success: 'bg-green-500 text-white border-green-600',
        error: 'bg-red-500 text-white border-red-600',
        warning: 'bg-yellow-500 text-black border-yellow-600',
        info: 'bg-blue-500 text-white border-blue-600'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3 px-6 py-4 rounded-xl shadow-lg border-2 ${styles[type]} min-w-80 max-w-md">
            <i class="${icons[type]} text-xl"></i>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-white/80 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Ajouter au DOM avec animation d'entrée
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto-suppression après 4 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 500);
        }
    }, 4000);
}

// Fonction pour filtrer les membres - VERSION CORRIGÉE POUR TOUTES LES VUES
function filtrerMembres() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('filterRole').value;
    const statutFilter = document.getElementById('filterStatut').value;
    
    console.log('=== FILTRAGE ===');
    console.log('Recherche:', searchTerm);
    console.log('Rôle:', roleFilter);
    console.log('Statut:', statutFilter);
    
    // Sélectionner tous les membres
    const membres = document.querySelectorAll('.member-card, .member-list-item, .member-table-row');
    console.log('Membres trouvés:', membres.length);
    
    membres.forEach((membre, index) => {
        let visible = true;
        
        // Recherche par nom - chercher dans h3 OU div selon la vue
        if (searchTerm) {
            let nomElement = membre.querySelector('h3');
            if (!nomElement) {
                // Pour la vue tableau, chercher dans div
                nomElement = membre.querySelector('div.text-white.font-medium');
            }
            const nom = nomElement ? nomElement.textContent.toLowerCase() : '';
            console.log(`Membre ${index} - Nom trouvé: "${nom}"`);
            if (!nom.includes(searchTerm)) {
                visible = false;
            }
        }
        
        // Filtre par statut - version ultra-simple et robuste
        if (visible && statutFilter) {
            // Chercher le texte "Actif", "Inactif" ou "Suspendu" n'importe où dans le membre
            const contenuMembre = membre.textContent.toLowerCase();
            const statutRecherche = statutFilter.toLowerCase();
            
            console.log(`Membre ${index} - Contenu: "${contenuMembre}"`);
            console.log(`Membre ${index} - Recherche: "${statutRecherche}"`);
            
            if (!contenuMembre.includes(statutRecherche)) {
                console.log(`Membre ${index} - Statut "${statutRecherche}" non trouvé`);
                visible = false;
            } else {
                console.log(`Membre ${index} - Statut "${statutRecherche}" trouvé`);
            }
        }
        
        // Filtre par rôle - chercher dans p OU span selon la vue
        if (visible && roleFilter) {
            let roleElement = membre.querySelector('p');
            if (!roleElement) {
                // Pour la vue tableau, chercher dans span
                roleElement = membre.querySelector('span.text-white\\/70');
            }
            const role = roleElement ? roleElement.textContent.toLowerCase() : '';
            const roleOption = document.querySelector(`#filterRole option[value="${roleFilter}"]`);
            const roleNom = roleOption ? roleOption.textContent.toLowerCase() : '';
            console.log(`Membre ${index} - Rôle trouvé: "${role}", Rôle sélectionné: "${roleNom}"`);
            if (!role.includes(roleNom)) {
                visible = false;
            }
        }
        
        console.log(`Membre ${index} - Visible: ${visible}`);
        // Afficher ou masquer
        membre.style.display = visible ? '' : 'none';
    });
}

// Fonction pour effacer les filtres
function effacerFiltres() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterRole').value = '';
    document.getElementById('filterStatut').value = '';
    
    const membres = document.querySelectorAll('.member-card, .member-list-item, .member-table-row');
    membres.forEach(membre => {
        membre.style.display = '';
    });
}

// Initialisation simple et efficace
document.addEventListener('DOMContentLoaded', function() {
    // Test de fonctionnement
    console.log('Initialisation des filtres...');
    console.log('searchInput:', document.getElementById('searchInput'));
    console.log('filterRole:', document.getElementById('filterRole'));
    console.log('filterStatut:', document.getElementById('filterStatut'));
    console.log('Membres trouvés:', document.querySelectorAll('.member-card, .member-list-item, .member-table-row').length);
    
    // Event listeners pour les filtres
    const searchInput = document.getElementById('searchInput');
    const filterRole = document.getElementById('filterRole');
    const filterStatut = document.getElementById('filterStatut');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            console.log('Recherche:', this.value);
            filtrerMembres();
        });
    }
    
    if (filterRole) {
        filterRole.addEventListener('change', function() {
            console.log('Rôle sélectionné:', this.value);
            filtrerMembres();
        });
    }
    
    if (filterStatut) {
        filterStatut.addEventListener('change', function() {
            console.log('Statut sélectionné:', this.value);
            filtrerMembres();
        });
    }
    
    console.log('Filtres initialisés avec succès !');
});

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
