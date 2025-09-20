<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Membres - Gestion Kourel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animations et transitions modernes */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .status-badge:hover::before {
            left: 100%;
        }
        
        .search-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div id="app" class="min-h-screen">
        <!-- Header moderne avec navigation -->
        <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-gray-200/50 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Gestion Kourel</h1>
                            <p class="text-sm text-gray-600">Liste des Membres</p>
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
                                class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:border-blue-300 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <i :class="getVueIcon(vueActuelle)" class="mr-2 text-blue-600"></i>
                            {{ getVueTexte(vueActuelle) }}
                        </button>
                        
                        <!-- Bouton d'ajout -->
                        <button @click="ajouterMembre" 
                                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-200 hover-lift">
                            <i class="fas fa-plus mr-2"></i>
                            Nouveau Membre
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Barre de recherche et filtres -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-8 fade-in">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Recherche -->
                    <div class="flex-1">
                        <div class="relative">
                            <input v-model="termeRecherche" 
                                   @input="rechercherMembres"
                                   type="text" 
                                   placeholder="Rechercher un membre..."
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent search-glow transition-all duration-200">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="flex gap-3">
                        <select v-model="filtreStatut" @change="filtrerMembres" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                            <option value="suspendu">Suspendu</option>
                        </select>
                        
                        <select v-model="filtreRole" @change="filtrerMembres" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les rôles</option>
                            <option value="1">Administrateur</option>
                            <option value="2">Membre</option>
                            <option value="3">Trésorier</option>
                        </select>
                    </div>
                </div>
                
                <!-- Statistiques rapides -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Total Membres</p>
                                <p class="text-2xl font-bold">{{ membresFiltres.length }}</p>
                            </div>
                            <i class="fas fa-users text-3xl text-blue-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm">Actifs</p>
                                <p class="text-2xl font-bold">{{ membresActifs }}</p>
                            </div>
                            <i class="fas fa-user-check text-3xl text-green-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm">Nouveaux</p>
                                <p class="text-2xl font-bold">{{ nouveauxMembres }}</p>
                            </div>
                            <i class="fas fa-user-plus text-3xl text-purple-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm">Taux Présence</p>
                                <p class="text-2xl font-bold">{{ tauxPresenceMoyen }}%</p>
                            </div>
                            <i class="fas fa-chart-line text-3xl text-orange-200"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue Liste -->
            <div v-if="vueActuelle === 'liste'" class="space-y-4 fade-in">
                <div v-for="membre in membresFiltres" :key="membre.id" 
                     class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover-lift slide-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar -->
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    {{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}
                                </div>
                                <div :class="getStatutColor(membre.statut)" 
                                     class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center">
                                    <i :class="getStatutIcon(membre.statut)" class="text-xs"></i>
                                </div>
                            </div>
                            
                            <!-- Informations -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ membre.prenom }} {{ membre.nom }}</h3>
                                <p class="text-gray-600">{{ membre.email }}</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ membre.telephone }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Adhésion: {{ formaterDate(membre.date_adhesion) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <div :class="getStatutBadgeClass(membre.statut)" 
                                     class="status-badge px-3 py-1 rounded-full text-sm font-medium">
                                    {{ getStatutTexte(membre.statut) }}
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Présence: {{ membre.taux_presence || 0 }}%
                                </p>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button @click="voirMembre(membre)" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button @click="modifierMembre(membre)" 
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="supprimerMembre(membre)" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue Grille -->
            <div v-if="vueActuelle === 'grille'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 fade-in">
                <div v-for="membre in membresFiltres" :key="membre.id" 
                     class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover-lift slide-in">
                    <!-- Header de la carte -->
                    <div class="text-center mb-4">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">
                                {{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}
                            </div>
                            <div :class="getStatutColor(membre.statut)" 
                                 class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center">
                                <i :class="getStatutIcon(membre.statut)" class="text-xs"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mt-3">{{ membre.prenom }} {{ membre.nom }}</h3>
                        <p class="text-gray-600 text-sm">{{ membre.email }}</p>
                    </div>
                    
                    <!-- Informations -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone w-4 mr-2"></i>
                            {{ membre.telephone }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar w-4 mr-2"></i>
                            {{ formaterDate(membre.date_adhesion) }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chart-line w-4 mr-2"></i>
                            Présence: {{ membre.taux_presence || 0 }}%
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div class="text-center mb-4">
                        <div :class="getStatutBadgeClass(membre.statut)" 
                             class="status-badge inline-block px-3 py-1 rounded-full text-sm font-medium">
                            {{ getStatutTexte(membre.statut) }}
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-center space-x-2">
                        <button @click="voirMembre(membre)" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button @click="modifierMembre(membre)" 
                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="supprimerMembre(membre)" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Vue Tableau -->
            <div v-if="vueActuelle === 'tableau'" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden fade-in">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('nom')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Membre</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('email')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Contact</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('date_adhesion')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Adhésion</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('statut')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Statut</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('taux_presence')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Présence</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="membre in membresFiltres" :key="membre.id" 
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                            {{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ membre.prenom }} {{ membre.nom }}</div>
                                            <div class="text-sm text-gray-500">{{ membre.telephone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ membre.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ formaterDate(membre.date_adhesion) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div :class="getStatutBadgeClass(membre.statut)" 
                                         class="status-badge inline-block px-3 py-1 rounded-full text-sm font-medium">
                                        {{ getStatutTexte(membre.statut) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div :style="{ width: (membre.taux_presence || 0) + '%' }" 
                                                 class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"></div>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ membre.taux_presence || 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button @click="voirMembre(membre)" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="modifierMembre(membre)" 
                                                class="text-green-600 hover:text-green-900 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="supprimerMembre(membre)" 
                                                class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Message si aucun membre -->
            <div v-if="membresFiltres.length === 0" class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun membre trouvé</h3>
                <p class="text-gray-600 mb-6">Aucun membre ne correspond à vos critères de recherche.</p>
                <button @click="ajouterMembre" 
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter le premier membre
                </button>
            </div>
        </main>

        <!-- Bouton d'action flottant -->
        <button @click="ajouterMembre" 
                class="floating-action bg-gradient-to-r from-blue-600 to-purple-600 text-white w-14 h-14 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 hover-lift">
            <i class="fas fa-plus text-xl"></i>
        </button>

    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    vueActuelle: 'liste',
                    termeRecherche: '',
                    filtreStatut: '',
                    filtreRole: '',
                    membres: [],
                    membresFiltres: [],
                    triColonne: '',
                    triDirection: 'asc'
                }
            },
            
            computed: {
                membresActifs() {
                    return this.membres.filter(m => m.statut === 'actif').length;
                },
                
                nouveauxMembres() {
                    const dernierMois = new Date();
                    dernierMois.setMonth(dernierMois.getMonth() - 1);
                    return this.membres.filter(m => new Date(m.date_adhesion) > dernierMois).length;
                },
                
                tauxPresenceMoyen() {
                    if (this.membres.length === 0) return 0;
                    const total = this.membres.reduce((sum, m) => sum + (m.taux_presence || 0), 0);
                    return Math.round(total / this.membres.length);
                }
            },
            
            mounted() {
                this.chargerMembres();
            },
            
            methods: {
                async chargerMembres() {
                    try {
                        // Récupération des vrais membres depuis la base de données
                        const response = await axios.get('/api/membres');
                        this.membres = response.data.map(membre => ({
                            id: membre.id,
                            nom: membre.nom,
                            prenom: membre.prenom,
                            email: membre.email || '',
                            telephone: membre.telephone || '',
                            date_adhesion: membre.date_adhesion,
                            statut: membre.statut,
                            taux_presence: membre.taux_presence || 0,
                            matricule: membre.matricule || '',
                            profession: membre.profession || '',
                            competences: membre.competences || []
                        }));
                        
                        this.membresFiltres = [...this.membres];
                        console.log('Membres chargés depuis la base de données:', this.membres.length);
                    } catch (error) {
                        console.error('Erreur lors du chargement des membres:', error);
                        // En cas d'erreur, utiliser les données de la base Laravel
                        this.membres = @json($membres ?? []);
                        this.membresFiltres = [...this.membres];
                    }
                },
                
                rechercherMembres() {
                    this.filtrerMembres();
                },
                
                filtrerMembres() {
                    let resultats = [...this.membres];
                    
                    // Filtre par terme de recherche
                    if (this.termeRecherche) {
                        const terme = this.termeRecherche.toLowerCase();
                        resultats = resultats.filter(membre => 
                            membre.nom.toLowerCase().includes(terme) ||
                            membre.prenom.toLowerCase().includes(terme) ||
                            membre.email.toLowerCase().includes(terme)
                        );
                    }
                    
                    // Filtre par statut
                    if (this.filtreStatut) {
                        resultats = resultats.filter(membre => membre.statut === this.filtreStatut);
                    }
                    
                    // Filtre par rôle
                    if (this.filtreRole) {
                        resultats = resultats.filter(membre => membre.role_id == this.filtreRole);
                    }
                    
                    this.membresFiltres = resultats;
                },
                
                trierPar(colonne) {
                    if (this.triColonne === colonne) {
                        this.triDirection = this.triDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.triColonne = colonne;
                        this.triDirection = 'asc';
                    }
                    
                    this.membresFiltres.sort((a, b) => {
                        let aVal = a[colonne];
                        let bVal = b[colonne];
                        
                        if (typeof aVal === 'string') {
                            aVal = aVal.toLowerCase();
                            bVal = bVal.toLowerCase();
                        }
                        
                        if (this.triDirection === 'asc') {
                            return aVal > bVal ? 1 : -1;
                        } else {
                            return aVal < bVal ? 1 : -1;
                        }
                    });
                },
                
                formaterDate(date) {
                    return new Date(date).toLocaleDateString('fr-FR');
                },
                
                getStatutColor(statut) {
                    const couleurs = {
                        'actif': 'bg-green-500',
                        'inactif': 'bg-gray-500',
                        'suspendu': 'bg-red-500'
                    };
                    return couleurs[statut] || 'bg-gray-500';
                },
                
                getStatutIcon(statut) {
                    const icones = {
                        'actif': 'fas fa-check',
                        'inactif': 'fas fa-pause',
                        'suspendu': 'fas fa-ban'
                    };
                    return icones[statut] || 'fas fa-question';
                },
                
                getStatutBadgeClass(statut) {
                    const classes = {
                        'actif': 'bg-green-100 text-green-800',
                        'inactif': 'bg-gray-100 text-gray-800',
                        'suspendu': 'bg-red-100 text-red-800'
                    };
                    return classes[statut] || 'bg-gray-100 text-gray-800';
                },
                
                getStatutTexte(statut) {
                    const textes = {
                        'actif': 'Actif',
                        'inactif': 'Inactif',
                        'suspendu': 'Suspendu'
                    };
                    return textes[statut] || 'Inconnu';
                },
                
                ajouterMembre() {
                    alert('Fonctionnalité d\'ajout de membre à implémenter');
                },
                
                voirMembre(membre) {
                    alert(`Voir le profil de ${membre.prenom} ${membre.nom}`);
                },
                
                modifierMembre(membre) {
                    alert(`Modifier ${membre.prenom} ${membre.nom}`);
                },
                
                supprimerMembre(membre) {
                    if (confirm(`Êtes-vous sûr de vouloir supprimer ${membre.prenom} ${membre.nom} ?`)) {
                        alert('Membre supprimé');
                    }
                },
                
                exporterCSV() {
                    const csvContent = this.genererCSV();
                    this.downloaderFichier(csvContent, 'membres.csv', 'text/csv');
                },
                
                exporterPDF() {
                    alert('Export PDF à implémenter avec une bibliothèque comme jsPDF');
                },
                
                exporterExcel() {
                    alert('Export Excel à implémenter avec une bibliothèque comme SheetJS');
                },
                
                genererCSV() {
                    const headers = ['Nom', 'Prénom', 'Email', 'Téléphone', 'Date Adhésion', 'Statut', 'Taux Présence'];
                    const rows = this.membresFiltres.map(membre => [
                        membre.nom,
                        membre.prenom,
                        membre.email,
                        membre.telephone,
                        this.formaterDate(membre.date_adhesion),
                        this.getStatutTexte(membre.statut),
                        (membre.taux_presence || 0) + '%'
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
                    const vues = ['liste', 'grille', 'tableau'];
                    const indexActuel = vues.indexOf(this.vueActuelle);
                    const prochainIndex = (indexActuel + 1) % vues.length;
                    this.vueActuelle = vues[prochainIndex];
                },
                
                getVueIcon(vue) {
                    const icones = {
                        'liste': 'fas fa-list',
                        'grille': 'fas fa-th',
                        'tableau': 'fas fa-table'
                    };
                    return icones[vue] || 'fas fa-list';
                },
                
                getVueTexte(vue) {
                    const textes = {
                        'liste': 'Liste',
                        'grille': 'Grille',
                        'tableau': 'Tableau'
                    };
                    return textes[vue] || 'Liste';
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
