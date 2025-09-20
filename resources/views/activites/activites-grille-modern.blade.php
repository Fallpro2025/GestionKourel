<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activités - Gestion Kourel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animations et effets modernes */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
        }
        
        .status-indicator {
            position: relative;
            overflow: hidden;
        }
        
        .status-indicator::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }
        
        .status-indicator:hover::before {
            left: 100%;
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        .floating-card {
            position: relative;
            overflow: hidden;
        }
        
        .floating-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .floating-card:hover::after {
            transform: translateX(100%);
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            transition: stroke-dashoffset 0.5s ease-in-out;
        }
        
        .timeline-item {
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 2px;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -26px;
            top: 8px;
            width: 12px;
            height: 12px;
            background: #3b82f6;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .masonry-grid {
            column-count: 1;
            column-gap: 1.5rem;
        }
        
        @media (min-width: 640px) {
            .masonry-grid {
                column-count: 2;
            }
        }
        
        @media (min-width: 1024px) {
            .masonry-grid {
                column-count: 3;
            }
        }
        
        @media (min-width: 1280px) {
            .masonry-grid {
                column-count: 4;
            }
        }
        
        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }
        
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-cyan-50 min-h-screen">
    <div id="app" class="min-h-screen">
        <!-- Header avec navigation moderne -->
        <header class="bg-white/90 backdrop-blur-md shadow-xl border-b border-gray-200/50 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center space-x-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Activités Kourel</h1>
                            <p class="text-gray-600">Gestion moderne des activités et événements</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Bouton d'export -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button @click="exporterCSV" 
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200 hover:bg-white">
                                <i class="fas fa-file-csv mr-1"></i>
                                CSV
                            </button>
                            <button @click="exporterPDF" 
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200 hover:bg-white">
                                <i class="fas fa-file-pdf mr-1"></i>
                                PDF
                            </button>
                            <button @click="exporterExcel" 
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200 hover:bg-white">
                                <i class="fas fa-file-excel mr-1"></i>
                                Excel
                            </button>
                        </div>
                        
                        <!-- Bouton Vue -->
                        <button @click="changerVueDirectement" 
                                class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <i :class="getVueIcon(vueActuelle)" class="mr-2 text-indigo-600"></i>
                            {{ getVueTexte(vueActuelle) }}
                        </button>
                        
                        <!-- Bouton d'ajout -->
                        <button @click="ajouterActivite" 
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle Activité
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistiques en temps réel -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Activités</p>
                            <p class="text-3xl font-bold">{{ activitesFiltrees.length }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-lg slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">En Cours</p>
                            <p class="text-3xl font-bold">{{ activitesEnCours }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center pulse-animation">
                            <i class="fas fa-play text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-lg slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">À Venir</p>
                            <p class="text-3xl font-bold">{{ activitesAVenir }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-lg slide-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Taux Participation</p>
                            <p class="text-3xl font-bold">{{ tauxParticipationMoyen }}%</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-pie text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barre de recherche et filtres avancés -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input v-model="termeRecherche" 
                                   @input="filtrerActivites"
                                   type="text" 
                                   placeholder="Rechercher une activité..."
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <select v-model="filtreType" @change="filtrerActivites" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Tous les types</option>
                            <option value="formation">Formation</option>
                            <option value="reunion">Réunion</option>
                            <option value="evenement">Événement</option>
                            <option value="autre">Autre</option>
                        </select>
                        
                        <select v-model="filtreStatut" @change="filtrerActivites" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="planifie">Planifié</option>
                            <option value="confirme">Confirmé</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Grille d'activités avec effet masonry -->
            <div class="masonry-grid">
                <div v-for="activite in activitesFiltrees" :key="activite.id" 
                     class="masonry-item">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden card-hover floating-card slide-up">
                        <!-- Header de la carte avec gradient -->
                        <div class="relative h-32 gradient-overlay">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
                            <div class="relative p-6 h-full flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ activite.nom }}</h3>
                                    <div :class="getStatutBadgeClass(activite.statut)" 
                                         class="status-indicator inline-block px-3 py-1 rounded-full text-sm font-medium">
                                        {{ getStatutTexte(activite.statut) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-2">
                                        <i :class="getTypeIcon(activite.type)" class="text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ getTypeTexte(activite.type) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenu de la carte -->
                        <div class="p-6">
                            <!-- Description -->
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ activite.description }}</p>
                            
                            <!-- Informations temporelles -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar w-4 mr-3 text-indigo-500"></i>
                                    <span>{{ formaterDate(activite.date_debut) }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 mr-3 text-indigo-500"></i>
                                    <span>{{ formaterHeure(activite.date_debut) }} - {{ formaterHeure(activite.date_fin) }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt w-4 mr-3 text-indigo-500"></i>
                                    <span>{{ activite.lieu }}</span>
                                </div>
                            </div>
                            
                            <!-- Responsable -->
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ activite.responsable?.prenom?.charAt(0) || 'R' }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Responsable</p>
                                    <p class="text-xs text-gray-600">{{ activite.responsable?.nom_complet || 'Non assigné' }}</p>
                                </div>
                            </div>
                            
                            <!-- Statistiques de participation -->
                            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Participation</span>
                                    <span class="text-sm text-gray-600">{{ activite.participation }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div :style="{ width: activite.participation + '%' }" 
                                         class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-500"></div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <button @click="voirActivite(activite)" 
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="modifierActivite(activite)" 
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="gererPresence(activite)" 
                                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                        <i class="fas fa-users"></i>
                                    </button>
                                </div>
                                
                                <button @click="supprimerActivite(activite)" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message si aucune activité -->
            <div v-if="activitesFiltrees.length === 0" class="text-center py-16">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-alt text-6xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune activité trouvée</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Aucune activité ne correspond à vos critères de recherche. 
                    Créez votre première activité pour commencer.
                </p>
                <button @click="ajouterActivite" 
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <i class="fas fa-plus mr-3"></i>
                    Créer une activité
                </button>
            </div>
        </main>

        <!-- Bouton d'action flottant -->
        <button @click="ajouterActivite" 
                class="fixed bottom-8 right-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white w-16 h-16 rounded-full shadow-xl hover:shadow-2xl transition-all duration-200 hover:scale-110 z-40">
            <i class="fas fa-plus text-xl"></i>
        </button>

    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    vueActuelle: 'grille',
                    filtreActuel: 'toutes',
                    termeRecherche: '',
                    filtreType: '',
                    filtreStatut: '',
                    activites: [],
                    activitesFiltrees: []
                }
            },
            
            computed: {
                activitesEnCours() {
                    return this.activites.filter(a => a.statut === 'en_cours').length;
                },
                
                activitesAVenir() {
                    return this.activites.filter(a => a.statut === 'planifie' || a.statut === 'confirme').length;
                },
                
                tauxParticipationMoyen() {
                    if (this.activites.length === 0) return 0;
                    const total = this.activites.reduce((sum, a) => sum + (a.participation || 0), 0);
                    return Math.round(total / this.activites.length);
                }
            },
            
            mounted() {
                this.chargerActivites();
            },
            
            methods: {
                async chargerActivites() {
                    try {
                        // Simulation de données - remplacer par un appel API réel
                        this.activites = [
                            {
                                id: 1,
                                nom: 'Formation Coranique',
                                description: 'Session de formation intensive sur la récitation et la mémorisation du Coran pour les membres débutants.',
                                type: 'formation',
                                date_debut: '2024-01-20T09:00:00',
                                date_fin: '2024-01-20T12:00:00',
                                lieu: 'Mosquée Centrale',
                                statut: 'confirme',
                                participation: 85,
                                responsable: {
                                    nom_complet: 'Cheikh Amadou DIOP',
                                    prenom: 'Amadou'
                                }
                            },
                            {
                                id: 2,
                                nom: 'Réunion Mensuelle',
                                description: 'Réunion mensuelle pour faire le point sur les activités et planifier les prochains événements.',
                                type: 'reunion',
                                date_debut: '2024-01-25T19:00:00',
                                date_fin: '2024-01-25T21:00:00',
                                lieu: 'Salle de Conférence',
                                statut: 'planifie',
                                participation: 92,
                                responsable: {
                                    nom_complet: 'Fatou FALL',
                                    prenom: 'Fatou'
                                }
                            },
                            {
                                id: 3,
                                nom: 'Magal de Touba',
                                description: 'Célébration du Magal de Touba avec participation à la procession et aux prières collectives.',
                                type: 'evenement',
                                date_debut: '2024-02-15T06:00:00',
                                date_fin: '2024-02-15T18:00:00',
                                lieu: 'Touba',
                                statut: 'en_cours',
                                participation: 78,
                                responsable: {
                                    nom_complet: 'Moussa NDIAYE',
                                    prenom: 'Moussa'
                                }
                            }
                        ];
                        
                        this.activitesFiltrees = [...this.activites];
                    } catch (error) {
                        console.error('Erreur lors du chargement des activités:', error);
                    }
                },
                
                filtrerActivites() {
                    let resultats = [...this.activites];
                    
                    // Filtre par terme de recherche
                    if (this.termeRecherche) {
                        const terme = this.termeRecherche.toLowerCase();
                        resultats = resultats.filter(activite => 
                            activite.nom.toLowerCase().includes(terme) ||
                            activite.description.toLowerCase().includes(terme) ||
                            activite.lieu.toLowerCase().includes(terme)
                        );
                    }
                    
                    // Filtre par type
                    if (this.filtreType) {
                        resultats = resultats.filter(activite => activite.type === this.filtreType);
                    }
                    
                    // Filtre par statut
                    if (this.filtreStatut) {
                        resultats = resultats.filter(activite => activite.statut === this.filtreStatut);
                    }
                    
                    // Filtre rapide
                    switch (this.filtreActuel) {
                        case 'avenir':
                            resultats = resultats.filter(a => a.statut === 'planifie' || a.statut === 'confirme');
                            break;
                        case 'cours':
                            resultats = resultats.filter(a => a.statut === 'en_cours');
                            break;
                        case 'terminees':
                            resultats = resultats.filter(a => a.statut === 'termine');
                            break;
                    }
                    
                    this.activitesFiltrees = resultats;
                },
                
                formaterDate(date) {
                    return new Date(date).toLocaleDateString('fr-FR', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },
                
                formaterHeure(date) {
                    return new Date(date).toLocaleTimeString('fr-FR', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                
                getStatutBadgeClass(statut) {
                    const classes = {
                        'planifie': 'bg-blue-100 text-blue-800',
                        'confirme': 'bg-green-100 text-green-800',
                        'en_cours': 'bg-purple-100 text-purple-800',
                        'termine': 'bg-gray-100 text-gray-800'
                    };
                    return classes[statut] || 'bg-gray-100 text-gray-800';
                },
                
                getStatutTexte(statut) {
                    const textes = {
                        'planifie': 'Planifié',
                        'confirme': 'Confirmé',
                        'en_cours': 'En cours',
                        'termine': 'Terminé'
                    };
                    return textes[statut] || 'Inconnu';
                },
                
                getTypeIcon(type) {
                    const icones = {
                        'formation': 'fas fa-graduation-cap',
                        'reunion': 'fas fa-users',
                        'evenement': 'fas fa-calendar-star',
                        'autre': 'fas fa-calendar-alt'
                    };
                    return icones[type] || 'fas fa-calendar-alt';
                },
                
                getTypeTexte(type) {
                    const textes = {
                        'formation': 'Formation',
                        'reunion': 'Réunion',
                        'evenement': 'Événement',
                        'autre': 'Autre'
                    };
                    return textes[type] || 'Inconnu';
                },
                
                ajouterActivite() {
                    alert('Fonctionnalité d\'ajout d\'activité à implémenter');
                },
                
                voirActivite(activite) {
                    alert(`Voir les détails de ${activite.nom}`);
                },
                
                modifierActivite(activite) {
                    alert(`Modifier ${activite.nom}`);
                },
                
                gererPresence(activite) {
                    alert(`Gérer la présence pour ${activite.nom}`);
                },
                
                supprimerActivite(activite) {
                    if (confirm(`Êtes-vous sûr de vouloir supprimer ${activite.nom} ?`)) {
                        alert('Activité supprimée');
                    }
                },
                
                exporterCSV() {
                    const csvContent = this.genererCSV();
                    this.downloaderFichier(csvContent, 'activites.csv', 'text/csv');
                },
                
                exporterPDF() {
                    alert('Export PDF à implémenter avec une bibliothèque comme jsPDF');
                },
                
                exporterExcel() {
                    alert('Export Excel à implémenter avec une bibliothèque comme SheetJS');
                },
                
                genererCSV() {
                    const headers = ['Nom', 'Type', 'Date Début', 'Date Fin', 'Lieu', 'Statut', 'Participation'];
                    const rows = this.activitesFiltrees.map(activite => [
                        activite.nom,
                        this.getTypeTexte(activite.type),
                        this.formaterDate(activite.date_debut),
                        this.formaterDate(activite.date_fin),
                        activite.lieu,
                        this.getStatutTexte(activite.statut),
                        activite.participation + '%'
                    ]);
                    
                    return [headers, ...rows].map(row => 
                        row.map(field => `"${field}"`).join(',')
                    ).join('\n');
                },
                
                downloaderFichier(contenu, nomFichier, typeMime) {
                    const blob = new Blob([contenu], { type: typeMime });
                    const url = window.URL.createObjectURL(blob);
                    const lien = document.createElement('a');
                    lien.href = url;
                    lien.download = nomFichier;
                    document.body.appendChild(lien);
                    lien.click();
                    document.body.removeChild(lien);
                    window.URL.revokeObjectURL(url);
                },
                
                changerVueDirectement() {
                    const vues = ['grille', 'liste', 'tableau'];
                    const indexActuel = vues.indexOf(this.vueActuelle);
                    const prochainIndex = (indexActuel + 1) % vues.length;
                    this.vueActuelle = vues[prochainIndex];
                },
                
                getVueIcon(vue) {
                    const icones = {
                        'grille': 'fas fa-th',
                        'liste': 'fas fa-list',
                        'tableau': 'fas fa-table'
                    };
                    return icones[vue] || 'fas fa-th';
                },
                
                getVueTexte(vue) {
                    const textes = {
                        'grille': 'Grille',
                        'liste': 'Liste',
                        'tableau': 'Tableau'
                    };
                    return textes[vue] || 'Grille';
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
