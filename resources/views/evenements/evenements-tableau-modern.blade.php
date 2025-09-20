<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - Gestion Kourel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animations et effets modernes */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .table-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .table-row:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .sortable-header {
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .sortable-header:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .sortable-header::after {
            content: '';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            opacity: 0.3;
        }
        
        .sortable-header.asc::after {
            border-bottom: 6px solid #3b82f6;
            opacity: 1;
        }
        
        .sortable-header.desc::after {
            border-top: 6px solid #3b82f6;
            opacity: 1;
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
        
        .progress-bar {
            position: relative;
            overflow: hidden;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
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
        
        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .filter-chip {
            transition: all 0.2s ease;
        }
        
        .filter-chip:hover {
            transform: scale(1.05);
        }
        
        .filter-chip.active {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .data-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table th:first-child {
            border-top-left-radius: 12px;
        }
        
        .data-table th:last-child {
            border-top-right-radius: 12px;
        }
        
        .data-table tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }
        
        .data-table tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
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
        
        .export-button {
            position: relative;
            overflow: hidden;
        }
        
        .export-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .export-button:hover::before {
            left: 100%;
        }
        
        .search-input {
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: scale(1.02);
        }
        
        .action-button {
            transition: all 0.2s ease;
        }
        
        .action-button:hover {
            transform: scale(1.1);
        }
        
        .pagination-button {
            transition: all 0.2s ease;
        }
        
        .pagination-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .pagination-button.active {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div id="app" class="min-h-screen">
        <!-- Header moderne -->
        <header class="bg-white/90 backdrop-blur-md shadow-xl border-b border-gray-200/50 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center space-x-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Événements Kourel</h1>
                            <p class="text-gray-600">Gestion avancée des événements et célébrations</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Boutons d'export -->
                        <div class="flex bg-gray-100 rounded-xl p-1">
                            <button @click="exporterCSV" 
                                    class="export-button px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200">
                                <i class="fas fa-file-csv mr-2"></i>
                                CSV
                            </button>
                            <button @click="exporterPDF" 
                                    class="export-button px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200">
                                <i class="fas fa-file-pdf mr-2"></i>
                                PDF
                            </button>
                            <button @click="exporterExcel" 
                                    class="export-button px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200">
                                <i class="fas fa-file-excel mr-2"></i>
                                Excel
                            </button>
                        </div>
                        
                        <!-- Bouton Vue -->
                        <button @click="changerVueDirectement" 
                                class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:border-purple-300 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <i :class="getVueIcon(vueActuelle)" class="mr-2 text-purple-600"></i>
                            {{ getVueTexte(vueActuelle) }}
                        </button>
                        
                        <!-- Bouton d'ajout -->
                        <button @click="ajouterEvenement" 
                                class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvel Événement
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistiques et filtres -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Statistiques -->
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Événements</p>
                                <p class="text-3xl font-bold">{{ evenementsFiltres.length }}</p>
                            </div>
                            <i class="fas fa-calendar-star text-3xl text-purple-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">À Venir</p>
                                <p class="text-3xl font-bold">{{ evenementsAVenir }}</p>
                            </div>
                            <i class="fas fa-clock text-3xl text-green-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">En Cours</p>
                                <p class="text-3xl font-bold">{{ evenementsEnCours }}</p>
                            </div>
                            <i class="fas fa-play text-3xl text-blue-200"></i>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Budget Total</p>
                                <p class="text-3xl font-bold">{{ budgetTotal }}k</p>
                            </div>
                            <i class="fas fa-coins text-3xl text-orange-200"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Filtres rapides -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres Rapides</h3>
                    <div class="space-y-3">
                        <button @click="filtreActuel = 'tous'" 
                                :class="filtreActuel === 'tous' ? 'active' : ''"
                                class="filter-chip w-full text-left px-3 py-2 rounded-lg bg-gray-100 text-gray-700">
                            <i class="fas fa-list mr-2"></i>
                            Tous les événements
                        </button>
                        <button @click="filtreActuel = 'majeurs'" 
                                :class="filtreActuel === 'majeurs' ? 'active' : ''"
                                class="filter-chip w-full text-left px-3 py-2 rounded-lg bg-gray-100 text-gray-700">
                            <i class="fas fa-star mr-2"></i>
                            Événements majeurs
                        </button>
                        <button @click="filtreActuel = 'avenir'" 
                                :class="filtreActuel === 'avenir' ? 'active' : ''"
                                class="filter-chip w-full text-left px-3 py-2 rounded-lg bg-gray-100 text-gray-700">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            À venir
                        </button>
                        <button @click="filtreActuel = 'termines'" 
                                :class="filtreActuel === 'termines' ? 'active' : ''"
                                class="filter-chip w-full text-left px-3 py-2 rounded-lg bg-gray-100 text-gray-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            Terminés
                        </button>
                    </div>
                </div>
            </div>

            <!-- Barre de recherche et filtres avancés -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input v-model="termeRecherche" 
                                   @input="filtrerEvenements"
                                   type="text" 
                                   placeholder="Rechercher un événement..."
                                   class="search-input w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <select v-model="filtreType" @change="filtrerEvenements" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Tous les types</option>
                            <option value="magal">Magal</option>
                            <option value="gamou">Gamou</option>
                            <option value="promokhane">Promokhane</option>
                            <option value="conference">Conférence</option>
                            <option value="formation">Formation</option>
                        </select>
                        
                        <select v-model="filtreStatut" @change="filtrerEvenements" 
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="planifie">Planifié</option>
                            <option value="confirme">Confirmé</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                        
                        <input v-model="filtreBudgetMin" 
                               @input="filtrerEvenements"
                               type="number" 
                               placeholder="Budget min"
                               class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent w-32">
                    </div>
                </div>
            </div>

            <!-- Tableau avancé -->
            <div class="table-container rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('nom')" 
                                            :class="getSortClass('nom')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Événement</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('type')" 
                                            :class="getSortClass('type')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Type</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('date_debut')" 
                                            :class="getSortClass('date_debut')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Date</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('lieu')" 
                                            :class="getSortClass('lieu')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Lieu</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('budget')" 
                                            :class="getSortClass('budget')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Budget</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button @click="trierPar('statut')" 
                                            :class="getSortClass('statut')"
                                            class="sortable-header flex items-center space-x-2 hover:text-gray-700">
                                        <span>Statut</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participation
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="evenement in evenementsPagination" :key="evenement.id" 
                                class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white text-lg font-bold mr-4">
                                            {{ evenement.nom.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ evenement.nom }}</div>
                                            <div class="text-sm text-gray-500">{{ evenement.description?.substring(0, 50) }}...</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i :class="getTypeIcon(evenement.type)" class="text-purple-500 mr-2"></i>
                                        <span class="text-sm text-gray-900">{{ getTypeTexte(evenement.type) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ formaterDate(evenement.date_debut) }}</div>
                                    <div class="text-sm text-gray-500">{{ formaterHeure(evenement.date_debut) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-900">{{ evenement.lieu }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ formaterBudget(evenement.budget) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div :class="getStatutBadgeClass(evenement.statut)" 
                                         class="status-indicator inline-block px-3 py-1 rounded-full text-sm font-medium">
                                        {{ getStatutTexte(evenement.statut) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                            <div :style="{ width: evenement.participation + '%' }" 
                                                 class="progress-bar bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full"></div>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ evenement.participation }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button @click="voirEvenement(evenement)" 
                                                class="action-button p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="modifierEvenement(evenement)" 
                                                class="action-button p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="gererParticipants(evenement)" 
                                                class="action-button p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <button @click="supprimerEvenement(evenement)" 
                                                class="action-button p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-8">
                <div class="text-sm text-gray-700">
                    Affichage de {{ (pageActuelle - 1) * elementsParPage + 1 }} à 
                    {{ Math.min(pageActuelle * elementsParPage, evenementsFiltres.length) }} 
                    sur {{ evenementsFiltres.length }} événements
                </div>
                
                <div class="flex space-x-2">
                    <button @click="pageActuelle = 1" 
                            :disabled="pageActuelle === 1"
                            class="pagination-button px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button @click="pageActuelle--" 
                            :disabled="pageActuelle === 1"
                            class="pagination-button px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-left"></i>
                    </button>
                    
                    <button v-for="page in pagesVisibles" :key="page"
                            @click="pageActuelle = page"
                            :class="page === pageActuelle ? 'active' : ''"
                            class="pagination-button px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        {{ page }}
                    </button>
                    
                    <button @click="pageActuelle++" 
                            :disabled="pageActuelle === nombrePages"
                            class="pagination-button px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button @click="pageActuelle = nombrePages" 
                            :disabled="pageActuelle === nombrePages"
                            class="pagination-button px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>

            <!-- Message si aucun événement -->
            <div v-if="evenementsFiltres.length === 0" class="text-center py-16">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-star text-6xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun événement trouvé</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Aucun événement ne correspond à vos critères de recherche. 
                    Créez votre premier événement pour commencer.
                </p>
                <button @click="ajouterEvenement" 
                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <i class="fas fa-plus mr-3"></i>
                    Créer un événement
                </button>
            </div>
        </main>

        <!-- Bouton d'action flottant -->
        <button @click="ajouterEvenement" 
                class="floating-action bg-gradient-to-r from-purple-600 to-pink-600 text-white w-16 h-16 rounded-full shadow-xl hover:shadow-2xl transition-all duration-200 hover:scale-110 z-40">
            <i class="fas fa-plus text-xl"></i>
        </button>

    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    vueActuelle: 'tableau',
                    filtreActuel: 'tous',
                    termeRecherche: '',
                    filtreType: '',
                    filtreStatut: '',
                    filtreBudgetMin: '',
                    evenements: [],
                    evenementsFiltres: [],
                    triColonne: '',
                    triDirection: 'asc',
                    pageActuelle: 1,
                    elementsParPage: 10
                }
            },
            
            computed: {
                evenementsAVenir() {
                    return this.evenements.filter(e => e.statut === 'planifie' || e.statut === 'confirme').length;
                },
                
                evenementsEnCours() {
                    return this.evenements.filter(e => e.statut === 'en_cours').length;
                },
                
                budgetTotal() {
                    const total = this.evenements.reduce((sum, e) => sum + (e.budget || 0), 0);
                    return Math.round(total / 1000);
                },
                
                nombrePages() {
                    return Math.ceil(this.evenementsFiltres.length / this.elementsParPage);
                },
                
                pagesVisibles() {
                    const pages = [];
                    const debut = Math.max(1, this.pageActuelle - 2);
                    const fin = Math.min(this.nombrePages, this.pageActuelle + 2);
                    
                    for (let i = debut; i <= fin; i++) {
                        pages.push(i);
                    }
                    
                    return pages;
                },
                
                evenementsPagination() {
                    const debut = (this.pageActuelle - 1) * this.elementsParPage;
                    const fin = debut + this.elementsParPage;
                    return this.evenementsFiltres.slice(debut, fin);
                }
            },
            
            mounted() {
                this.chargerEvenements();
            },
            
            methods: {
                async chargerEvenements() {
                    try {
                        // Simulation de données - remplacer par un appel API réel
                        this.evenements = [
                            {
                                id: 1,
                                nom: 'Magal de Touba',
                                description: 'Célébration annuelle du Magal de Touba avec procession et prières collectives',
                                type: 'magal',
                                date_debut: '2024-02-15T06:00:00',
                                date_fin: '2024-02-15T18:00:00',
                                lieu: 'Touba',
                                budget: 500000,
                                statut: 'confirme',
                                participation: 95
                            },
                            {
                                id: 2,
                                nom: 'Gamou de Tivaouane',
                                description: 'Célébration du Gamou de Tivaouane avec participation de la communauté',
                                type: 'gamou',
                                date_debut: '2024-03-20T08:00:00',
                                date_fin: '2024-03-20T20:00:00',
                                lieu: 'Tivaouane',
                                budget: 300000,
                                statut: 'planifie',
                                participation: 78
                            },
                            {
                                id: 3,
                                nom: 'Promokhane de Dakar',
                                description: 'Promokhane annuel avec activités culturelles et religieuses',
                                type: 'promokhane',
                                date_debut: '2024-04-10T09:00:00',
                                date_fin: '2024-04-10T22:00:00',
                                lieu: 'Dakar',
                                budget: 750000,
                                statut: 'en_cours',
                                participation: 88
                            },
                            {
                                id: 4,
                                nom: 'Conférence Islamique',
                                description: 'Conférence sur les valeurs islamiques et la spiritualité',
                                type: 'conference',
                                date_debut: '2024-01-25T19:00:00',
                                date_fin: '2024-01-25T21:00:00',
                                lieu: 'Salle de Conférence',
                                budget: 150000,
                                statut: 'termine',
                                participation: 92
                            }
                        ];
                        
                        this.evenementsFiltres = [...this.evenements];
                    } catch (error) {
                        console.error('Erreur lors du chargement des événements:', error);
                    }
                },
                
                filtrerEvenements() {
                    let resultats = [...this.evenements];
                    
                    // Filtre par terme de recherche
                    if (this.termeRecherche) {
                        const terme = this.termeRecherche.toLowerCase();
                        resultats = resultats.filter(evenement => 
                            evenement.nom.toLowerCase().includes(terme) ||
                            evenement.description.toLowerCase().includes(terme) ||
                            evenement.lieu.toLowerCase().includes(terme)
                        );
                    }
                    
                    // Filtre par type
                    if (this.filtreType) {
                        resultats = resultats.filter(evenement => evenement.type === this.filtreType);
                    }
                    
                    // Filtre par statut
                    if (this.filtreStatut) {
                        resultats = resultats.filter(evenement => evenement.statut === this.filtreStatut);
                    }
                    
                    // Filtre par budget minimum
                    if (this.filtreBudgetMin) {
                        resultats = resultats.filter(evenement => evenement.budget >= this.filtreBudgetMin);
                    }
                    
                    // Filtre rapide
                    switch (this.filtreActuel) {
                        case 'majeurs':
                            resultats = resultats.filter(e => ['magal', 'gamou', 'promokhane'].includes(e.type));
                            break;
                        case 'avenir':
                            resultats = resultats.filter(e => e.statut === 'planifie' || e.statut === 'confirme');
                            break;
                        case 'termines':
                            resultats = resultats.filter(e => e.statut === 'termine');
                            break;
                    }
                    
                    this.evenementsFiltres = resultats;
                    this.pageActuelle = 1; // Reset pagination
                },
                
                trierPar(colonne) {
                    if (this.triColonne === colonne) {
                        this.triDirection = this.triDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.triColonne = colonne;
                        this.triDirection = 'asc';
                    }
                    
                    this.evenementsFiltres.sort((a, b) => {
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
                
                getSortClass(colonne) {
                    if (this.triColonne === colonne) {
                        return `sortable-header ${this.triDirection}`;
                    }
                    return 'sortable-header';
                },
                
                formaterDate(date) {
                    return new Date(date).toLocaleDateString('fr-FR', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                },
                
                formaterHeure(date) {
                    return new Date(date).toLocaleTimeString('fr-FR', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                
                formaterBudget(budget) {
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                        minimumFractionDigits: 0
                    }).format(budget);
                },
                
                getStatutBadgeClass(statut) {
                    const classes = {
                        'planifie': 'bg-blue-100 text-blue-800',
                        'confirme': 'bg-green-100 text-green-800',
                        'en_cours': 'bg-purple-100 text-purple-800',
                        'termine': 'bg-gray-100 text-gray-800',
                        'annule': 'bg-red-100 text-red-800'
                    };
                    return classes[statut] || 'bg-gray-100 text-gray-800';
                },
                
                getStatutTexte(statut) {
                    const textes = {
                        'planifie': 'Planifié',
                        'confirme': 'Confirmé',
                        'en_cours': 'En cours',
                        'termine': 'Terminé',
                        'annule': 'Annulé'
                    };
                    return textes[statut] || 'Inconnu';
                },
                
                getTypeIcon(type) {
                    const icones = {
                        'magal': 'fas fa-star',
                        'gamou': 'fas fa-moon',
                        'promokhane': 'fas fa-calendar-star',
                        'conference': 'fas fa-microphone',
                        'formation': 'fas fa-graduation-cap'
                    };
                    return icones[type] || 'fas fa-calendar-alt';
                },
                
                getTypeTexte(type) {
                    const textes = {
                        'magal': 'Magal',
                        'gamou': 'Gamou',
                        'promokhane': 'Promokhane',
                        'conference': 'Conférence',
                        'formation': 'Formation'
                    };
                    return textes[type] || 'Inconnu';
                },
                
                ajouterEvenement() {
                    alert('Fonctionnalité d\'ajout d\'événement à implémenter');
                },
                
                voirEvenement(evenement) {
                    alert(`Voir les détails de ${evenement.nom}`);
                },
                
                modifierEvenement(evenement) {
                    alert(`Modifier ${evenement.nom}`);
                },
                
                gererParticipants(evenement) {
                    alert(`Gérer les participants pour ${evenement.nom}`);
                },
                
                supprimerEvenement(evenement) {
                    if (confirm(`Êtes-vous sûr de vouloir supprimer ${evenement.nom} ?`)) {
                        alert('Événement supprimé');
                    }
                },
                
                exporterCSV() {
                    alert('Export CSV à implémenter');
                },
                
                exporterPDF() {
                    alert('Export PDF à implémenter');
                },
                
                exporterExcel() {
                    alert('Export Excel à implémenter avec une bibliothèque comme SheetJS');
                },
                
                changerVueDirectement() {
                    const vues = ['tableau', 'grille', 'liste'];
                    const indexActuel = vues.indexOf(this.vueActuelle);
                    const prochainIndex = (indexActuel + 1) % vues.length;
                    this.vueActuelle = vues[prochainIndex];
                },
                
                getVueIcon(vue) {
                    const icones = {
                        'tableau': 'fas fa-table',
                        'grille': 'fas fa-th',
                        'liste': 'fas fa-list'
                    };
                    return icones[vue] || 'fas fa-table';
                },
                
                getVueTexte(vue) {
                    const textes = {
                        'tableau': 'Tableau',
                        'grille': 'Grille',
                        'liste': 'Liste'
                    };
                    return textes[vue] || 'Tableau';
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
