@extends('layouts.app-with-sidebar')

@section('title', 'Dashboard - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                    <span class="ml-3 px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-full border border-green-500/30">
                        Gestion Kourel
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white/80 text-sm">Bienvenue</p>
                        <p class="text-white font-semibold">Administrateur</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">A</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Membres Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm">Total Membres</p>
                        <p class="text-3xl font-bold text-white">24</p>
                        <p class="text-green-400 text-sm">+3 ce mois</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Activités Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm">Activités</p>
                        <p class="text-3xl font-bold text-white">12</p>
                        <p class="text-blue-400 text-sm">Cette semaine</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Événements Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm">Événements</p>
                        <p class="text-3xl font-bold text-white">8</p>
                        <p class="text-purple-400 text-sm">À venir</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Présence Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm">Présence</p>
                        <p class="text-3xl font-bold text-white">89%</p>
                        <p class="text-orange-400 text-sm">Moyenne</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Actions Rapides -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h2 class="text-xl font-bold text-white mb-6">Actions Rapides</h2>
                <div class="space-y-4">
                    <a href="/membres" class="flex items-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-users text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold">Gestion des Membres</p>
                            <p class="text-white/70 text-sm">Ajouter, modifier, consulter</p>
                        </div>
                    </a>
                    
                    <a href="/activites" class="flex items-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold">Planifier une Activité</p>
                            <p class="text-white/70 text-sm">Répétitions, événements</p>
                        </div>
                    </a>
                    
                    <a href="/evenements" class="flex items-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all duration-300">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-star text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold">Gestion des Événements</p>
                            <p class="text-white/70 text-sm">Concerts, prestations</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Activités Récentes -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                <h2 class="text-xl font-bold text-white mb-6">Activités Récentes</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-white/5 rounded-xl">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-music text-green-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold">Répétition Chorale</p>
                            <p class="text-white/70 text-sm">Hier à 19h00</p>
                        </div>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Terminée</span>
                    </div>
                    
                    <div class="flex items-center p-4 bg-white/5 rounded-xl">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user-plus text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold">Nouveau Membre</p>
                            <p class="text-white/70 text-sm">Fatou Diop ajoutée</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full">Aujourd'hui</span>
                    </div>
                    
                    <div class="flex items-center p-4 bg-white/5 rounded-xl">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-star text-purple-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold">Concert de Noël</p>
                            <p class="text-white/70 text-sm">25 décembre 2025</p>
                        </div>
                        <span class="px-2 py-1 bg-purple-500/20 text-purple-400 text-xs rounded-full">À venir</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
