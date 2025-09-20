@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Membres - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white">👥 Gestion des Membres</h1>
                    <span class="ml-3 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        {{ $membres->count() }} membres
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Bouton Export -->
                    <div class="relative">
                        <button onclick="toggleExportOptions()" class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl flex items-center space-x-2 hover:bg-white/20 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Exporter</span>
                        </button>
                    </div>
                    
                    <!-- Bouton Vue -->
                    <div class="relative">
                        <button onclick="changerVueDirectement()" class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl flex items-center space-x-2 hover:bg-white/20 transition-all duration-300">
                            <i id="vueIcon" class="fas fa-th w-5 h-5"></i>
                            <span id="vueTexte">Grille</span>
                        </button>
                    </div>
                    
                    <!-- Bouton Ajouter Membre -->
                    <a href="{{ route('membres.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter Membre
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/30 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <span class="text-green-400 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-400 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

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
                    <select class="px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Tous les rôles</option>
                        <option value="choriste">Choriste</option>
                        <option value="soliste">Soliste</option>
                        <option value="responsable">Responsable</option>
                        <option value="musicien">Musicien</option>
                        <option value="technicien">Technicien</option>
                        <option value="administrateur">Administrateur</option>
                    </select>
                    <select class="px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Tous les statuts</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="suspendu">Suspendu</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Liste des membres -->
        <div id="membersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($membres as $membre)
                <div class="member-card bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            @if($membre->photo_url)
                                <img src="{{ Storage::url($membre->photo_url) }}" alt="{{ $membre->nom }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <span class="text-white font-bold text-lg">{{ substr($membre->prenom, 0, 1) }}{{ substr($membre->nom, 0, 1) }}</span>
                            @endif
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
                        <a href="{{ route('membres.show', $membre) }}" class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300 text-center">
                            <i class="fas fa-eye mr-1"></i>
                            Voir
                        </a>
                        <a href="{{ route('membres.edit', $membre) }}" class="flex-1 px-3 py-2 bg-blue-500/20 text-blue-400 text-xs rounded-lg hover:bg-blue-500/30 transition-all duration-300 text-center">
                            <i class="fas fa-edit mr-1"></i>
                            Modifier
                        </a>
                        <form action="{{ route('membres.destroy', $membre) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-500/20 text-red-400 text-xs rounded-lg hover:bg-red-500/30 transition-all duration-300">
                                <i class="fas fa-trash mr-1"></i>
                                Supprimer
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

// Fonctions de changement de vue (simplifiées pour cette version)
function switchToGridView() {
    const container = document.getElementById('membersGrid');
    if (container) {
        container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
    }
}

function switchToListView() {
    const container = document.getElementById('membersGrid');
    if (container) {
        container.className = 'flex flex-col space-y-4';
        // Transformer les cartes en format liste
        const cards = document.querySelectorAll('.member-card');
        cards.forEach(card => {
            card.className = 'member-card flex items-center justify-between p-4 bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300';
        });
    }
}

function switchToTableView() {
    const container = document.getElementById('membersGrid');
    if (container) {
        container.className = 'space-y-2';
        // Transformer les cartes en format tableau
        const cards = document.querySelectorAll('.member-card');
        cards.forEach(card => {
            card.className = 'member-card grid grid-cols-6 gap-4 p-4 items-center bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300';
        });
    }
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

// Fonction pour les options d'export
function toggleExportOptions() {
    const formats = ['CSV', 'PDF', 'Excel'];
    const choice = prompt('Choisir le format d\'export:\n1. CSV\n2. PDF\n3. Excel\n\nEntrez 1, 2 ou 3:');
    
    if (choice === '1') {
        window.location.href = '{{ route("membres.export") }}?format=csv';
    } else if (choice === '2') {
        alert('Export PDF en cours...\n\nFonctionnalité à implémenter avec une bibliothèque PDF');
    } else if (choice === '3') {
        alert('Export Excel en cours...\n\nFonctionnalité à implémenter avec une bibliothèque Excel');
    }
}
</script>
@endsection
