@extends('layouts.app-with-sidebar')

@section('title', 'Statistiques des Membres - Gestion Kourel')

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
                        <h1 class="text-3xl font-bold text-white"><i class="fas fa-chart-bar mr-3"></i>Statistiques des Membres</h1>
                        <p class="text-white/70 mt-1">Analyse complète des données membres</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="actualiserStatistiques()" 
                            class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-sync-alt mr-2"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Cartes de statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total des membres -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Total des membres</p>
                        <p class="text-3xl font-bold text-white" id="totalMembres">-</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-400 text-sm font-medium" id="evolutionTotal">
                        <i class="fas fa-arrow-up mr-1"></i>+0%
                    </span>
                    <span class="text-white/60 text-sm ml-2">vs mois dernier</span>
                </div>
            </div>

            <!-- Membres actifs -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Membres actifs</p>
                        <p class="text-3xl font-bold text-white" id="membresActifs">-</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-user-check text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-400 text-sm font-medium" id="pourcentageActifs">
                        0%
                    </span>
                    <span class="text-white/60 text-sm ml-2">du total</span>
                </div>
            </div>

            <!-- Nouveaux membres (mois) -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Nouveaux (mois)</p>
                        <p class="text-3xl font-bold text-white" id="nouveauxMois">-</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                        <i class="fas fa-user-plus text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-purple-400 text-sm font-medium" id="evolutionNouveaux">
                        <i class="fas fa-arrow-up mr-1"></i>+0%
                    </span>
                    <span class="text-white/60 text-sm ml-2">vs mois dernier</span>
                </div>
            </div>

            <!-- Âge moyen -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/70 text-sm font-medium">Âge moyen</p>
                        <p class="text-3xl font-bold text-white" id="ageMoyen">-</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-orange-500/20 flex items-center justify-center">
                        <i class="fas fa-birthday-cake text-orange-400 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-orange-400 text-sm font-medium">
                        ans
                    </span>
                    <span class="text-white/60 text-sm ml-2">moyenne</span>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Répartition par statut -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-pie-chart mr-3 text-blue-400"></i>
                    Répartition par statut
                </h3>
                <div id="chartStatut" class="h-64 flex items-center justify-center">
                    <div class="text-white/60">Chargement du graphique...</div>
                </div>
            </div>

            <!-- Répartition par rôle -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-pie mr-3 text-green-400"></i>
                    Répartition par rôle
                </h3>
                <div id="chartRole" class="h-64 flex items-center justify-center">
                    <div class="text-white/60">Chargement du graphique...</div>
                </div>
            </div>
        </div>

        <!-- Tableaux détaillés -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top professions -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-briefcase mr-3 text-purple-400"></i>
                    Top professions
                </h3>
                <div id="topProfessions" class="space-y-3">
                    <div class="text-white/60 text-center py-8">Chargement...</div>
                </div>
            </div>

            <!-- Évolution mensuelle -->
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-orange-400"></i>
                    Évolution mensuelle
                </h3>
                <div id="evolutionMensuelle" class="space-y-3">
                    <div class="text-white/60 text-center py-8">Chargement...</div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let statsData = null;
let chartStatut = null;
let chartRole = null;

// Charger les statistiques au démarrage
document.addEventListener('DOMContentLoaded', function() {
    chargerStatistiques();
});

// Fonction pour charger les statistiques
async function chargerStatistiques() {
    try {
        const response = await fetch('/membres/statistiques/api');
        statsData = await response.json();
        
        // Mettre à jour les cartes principales
        document.getElementById('totalMembres').textContent = statsData.total;
        document.getElementById('membresActifs').textContent = statsData.actifs;
        document.getElementById('nouveauxMois').textContent = statsData.nouveaux_mois;
        document.getElementById('ageMoyen').textContent = Math.round(statsData.age_moyen || 0);
        
        // Calculer les pourcentages
        const pourcentageActifs = statsData.total > 0 ? Math.round((statsData.actifs / statsData.total) * 100) : 0;
        document.getElementById('pourcentageActifs').textContent = pourcentageActifs + '%';
        
        // Créer les graphiques
        creerGraphiqueStatut();
        creerGraphiqueRole();
        
        // Mettre à jour les tableaux
        mettreAJourTopProfessions();
        mettreAJourEvolutionMensuelle();
        
    } catch (error) {
        console.error('Erreur lors du chargement des statistiques:', error);
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Erreur lors du chargement des statistiques');
        }
    }
}

// Créer le graphique de répartition par statut
function creerGraphiqueStatut() {
    const ctx = document.createElement('canvas');
    ctx.id = 'chartStatutCanvas';
    document.getElementById('chartStatut').innerHTML = '';
    document.getElementById('chartStatut').appendChild(ctx);
    
    chartStatut = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Inactifs', 'Suspendus'],
            datasets: [{
                data: [statsData.actifs, statsData.inactifs, statsData.suspendus],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(156, 163, 175, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(156, 163, 175, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });
}

// Créer le graphique de répartition par rôle
function creerGraphiqueRole() {
    const ctx = document.createElement('canvas');
    ctx.id = 'chartRoleCanvas';
    document.getElementById('chartRole').innerHTML = '';
    document.getElementById('chartRole').appendChild(ctx);
    
    const rolesData = statsData.par_role || [];
    const labels = rolesData.map(item => item.role);
    const data = rolesData.map(item => item.count);
    
    chartRole = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de membres',
                data: data,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
}

// Mettre à jour le tableau des top professions
function mettreAJourTopProfessions() {
    const professions = statsData.par_profession || [];
    const container = document.getElementById('topProfessions');
    
    if (professions.length === 0) {
        container.innerHTML = '<div class="text-white/60 text-center py-8">Aucune donnée disponible</div>';
        return;
    }
    
    container.innerHTML = professions.slice(0, 5).map((profession, index) => `
        <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center mr-3">
                    <span class="text-white font-bold text-xs">${index + 1}</span>
                </div>
                <div>
                    <p class="text-white font-medium">${profession.profession || 'Non renseignée'}</p>
                </div>
            </div>
            <span class="text-white font-bold">${profession.count}</span>
        </div>
    `).join('');
}

// Mettre à jour l'évolution mensuelle
function mettreAJourEvolutionMensuelle() {
    // Simuler des données d'évolution pour l'instant
    const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'];
    const evolution = [12, 15, 18, 22, 25, statsData.nouveaux_mois];
    
    const container = document.getElementById('evolutionMensuelle');
    container.innerHTML = evolution.map((valeur, index) => `
        <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center mr-3">
                    <span class="text-white font-bold text-xs">${index + 1}</span>
                </div>
                <div>
                    <p class="text-white font-medium">${mois[index]}</p>
                </div>
            </div>
            <span class="text-white font-bold">${valeur}</span>
        </div>
    `).join('');
}

// Fonction pour actualiser les statistiques
function actualiserStatistiques() {
    if (typeof alerteModerne !== 'undefined') {
        alerteModerne.information('Actualisation des statistiques...');
    }
    chargerStatistiques();
}
</script>
@endsection
