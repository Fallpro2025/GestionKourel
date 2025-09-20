// Application Vue.js moderne pour Gestion Kourel
const { createApp, ref, reactive, computed, onMounted, nextTick } = Vue;

// Données réactives globales
const appData = reactive({
    currentView: 'dashboard',
    membres: [
        { id: 1, nom: 'Fall', prenom: 'Moussa', telephone: '+221 77 123 45 67', role: 'Président', statut: 'actif', cotisations: 150000, presence: 85 },
        { id: 2, nom: 'Ndiaye', prenom: 'Aminata', telephone: '+221 78 234 56 78', role: 'Secrétaire', statut: 'actif', cotisations: 120000, presence: 92 },
        { id: 3, nom: 'Diop', prenom: 'Ibrahima', telephone: '+221 76 345 67 89', role: 'Membre', statut: 'actif', cotisations: 100000, presence: 78 },
        { id: 4, nom: 'Sow', prenom: 'Fatou', telephone: '+221 77 456 78 90', role: 'Trésorier', statut: 'actif', cotisations: 130000, presence: 88 }
    ],
    activites: [
        { id: 1, nom: 'Répétition hebdomadaire', type: 'répétition', jour: 'Dimanche', heure: '15:00', lieu: 'Mosquée centrale', statut: 'active' },
        { id: 2, nom: 'Goudi Aldiouma', type: 'goudi', jour: 'Vendredi', heure: '19:00', lieu: 'Maison du président', statut: 'active' },
        { id: 3, nom: 'Prestation Gamou', type: 'prestation', jour: 'Samedi', heure: '20:00', lieu: 'Place publique', statut: 'planifiée' }
    ],
    evenements: [
        { id: 1, nom: 'Magal de Touba', date: '2025-01-20', type: 'magal', statut: 'planifié', participants: 25 },
        { id: 2, nom: 'Gamou de Tivaouane', date: '2025-02-15', type: 'gamou', statut: 'planifié', participants: 30 },
        { id: 3, nom: 'Promokhane', date: '2025-03-10', type: 'promokhane', statut: 'en_cours', participants: 20 }
    ],
    alertes: [
        { id: 1, type: 'absence', message: '3 absences consécutives pour Diop Ibrahima', priorite: 'haute', date: '2025-01-13' },
        { id: 2, type: 'cotisation', message: 'Retard de paiement pour Sow Fatou', priorite: 'moyenne', date: '2025-01-12' },
        { id: 3, type: 'evenement', message: 'Rappel: Magal de Touba dans 7 jours', priorite: 'basse', date: '2025-01-13' }
    ],
    statistiques: {
        totalMembres: 0,
        membresActifs: 0,
        cotisationsTotales: 0,
        activitesActives: 0,
        evenementsPlanifies: 0,
        alertesEnCours: 0
    }
});

// Composant Dashboard moderne
const Dashboard = {
    template: `
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
            <!-- Header moderne avec glassmorphism -->
            <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-white/20 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <span class="text-white font-bold text-xl">DK</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Gestion Kourel</h1>
                                <p class="text-sm text-gray-600">Plateforme moderne de gestion</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button @click="showNotifications = !showNotifications" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75v10.5A2.25 2.25 0 004.5 19.5z"></path>
                                </svg>
                                <span v-if="alertesEnCours > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ alertesEnCours }}</span>
                            </button>
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Navigation moderne -->
            <nav class="bg-white/60 backdrop-blur-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex space-x-8">
                        <button @click="appData.currentView = 'dashboard'" 
                                :class="['py-4 px-2 border-b-2 font-medium text-sm transition-colors', 
                                        appData.currentView === 'dashboard' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']">
                            📊 Dashboard
                        </button>
                        <button @click="appData.currentView = 'membres'" 
                                :class="['py-4 px-2 border-b-2 font-medium text-sm transition-colors', 
                                        appData.currentView === 'membres' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']">
                            👥 Membres
                        </button>
                        <button @click="appData.currentView = 'cotisations'" 
                                :class="['py-4 px-2 border-b-2 font-medium text-sm transition-colors', 
                                        appData.currentView === 'cotisations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']">
                            💰 Cotisations
                        </button>
                        <button @click="appData.currentView = 'activites'" 
                                :class="['py-4 px-2 border-b-2 font-medium text-sm transition-colors', 
                                        appData.currentView === 'activites' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']">
                            📅 Activités
                        </button>
                        <button @click="appData.currentView = 'evenements'" 
                                :class="['py-4 px-2 border-b-2 font-medium text-sm transition-colors', 
                                        appData.currentView === 'evenements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700']">
                            🎤 Événements
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Contenu principal -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Statistiques en temps réel -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Membres</p>
                                <p class="text-3xl font-bold text-gray-900">{{ appData.statistiques.totalMembres }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+12%</span>
                            <span class="text-gray-500 ml-2">vs mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Cotisations</p>
                                <p class="text-3xl font-bold text-gray-900">{{ formatMoney(appData.statistiques.cotisationsTotales) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium">+8%</span>
                            <span class="text-gray-500 ml-2">vs mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Activités</p>
                                <p class="text-3xl font-bold text-gray-900">{{ appData.statistiques.activitesActives }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-blue-600 font-medium">3 actives</span>
                            <span class="text-gray-500 ml-2">cette semaine</span>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Événements</p>
                                <p class="text-3xl font-bold text-gray-900">{{ appData.statistiques.evenementsPlanifies }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75v10.5A2.25 2.25 0 004.5 19.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-orange-600 font-medium">2 à venir</span>
                            <span class="text-gray-500 ml-2">ce mois</span>
                        </div>
                    </div>
                </div>

                <!-- Contenu dynamique selon la vue -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-8">
                    <component :is="currentComponent"></component>
                </div>
            </main>
        </div>
    `,
    setup() {
        const showNotifications = ref(false);
        
        const alertesEnCours = computed(() => appData.alertes.length);
        
        const formatMoney = (amount) => {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'XOF'
            }).format(amount);
        };

        const currentComponent = computed(() => {
            switch(appData.currentView) {
                case 'dashboard': return DashboardContent;
                case 'membres': return MembresComponent;
                case 'cotisations': return CotisationsComponent;
                case 'activites': return ActivitesComponent;
                case 'evenements': return EvenementsComponent;
                default: return DashboardContent;
            }
        });

        onMounted(() => {
            // Calculer les statistiques
            appData.statistiques.totalMembres = appData.membres.length;
            appData.statistiques.membresActifs = appData.membres.filter(m => m.statut === 'actif').length;
            appData.statistiques.cotisationsTotales = appData.membres.reduce((sum, m) => sum + m.cotisations, 0);
            appData.statistiques.activitesActives = appData.activites.filter(a => a.statut === 'active').length;
            appData.statistiques.evenementsPlanifies = appData.evenements.filter(e => e.statut === 'planifié').length;
            appData.statistiques.alertesEnCours = appData.alertes.length;
        });

        return {
            appData,
            showNotifications,
            alertesEnCours,
            formatMoney,
            currentComponent
        };
    }
};

// Composant contenu Dashboard
const DashboardContent = {
    template: `
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-8">📊 Dashboard Moderne</h2>
            
            <!-- Graphiques et analyses -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4">📈 Tendances des Présences</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span>Moyenne générale</span>
                            <span class="font-bold">{{ moyennePresence }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" :style="{width: moyennePresence + '%'}"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4">💰 Recouvrement Cotisations</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span>Taux de recouvrement</span>
                            <span class="font-bold">{{ tauxRecouvrement }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" :style="{width: tauxRecouvrement + '%'}"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes récentes -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">🚨 Alertes Récentes</h3>
                <div class="space-y-4">
                    <div v-for="alerte in appData.alertes.slice(0, 3)" :key="alerte.id" 
                         :class="['p-4 rounded-xl border-l-4', 
                                 alerte.priorite === 'haute' ? 'bg-red-50 border-red-500' : 
                                 alerte.priorite === 'moyenne' ? 'bg-yellow-50 border-yellow-500' : 
                                 'bg-blue-50 border-blue-500']">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">{{ alerte.message }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ formatDate(alerte.date) }}</p>
                            </div>
                            <span :class="['px-2 py-1 rounded-full text-xs font-medium',
                                        alerte.priorite === 'haute' ? 'bg-red-100 text-red-800' : 
                                        alerte.priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-blue-100 text-blue-800']">
                                {{ alerte.priorite }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activités récentes -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">📅 Activités Récentes</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="activite in appData.activites.slice(0, 3)" :key="activite.id" 
                         class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">{{ activite.nom }}</h4>
                            <span :class="['px-2 py-1 rounded-full text-xs font-medium',
                                        activite.statut === 'active' ? 'bg-green-100 text-green-800' : 
                                        'bg-blue-100 text-blue-800']">
                                {{ activite.statut }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ activite.jour }} à {{ activite.heure }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ activite.lieu }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup() {
        const moyennePresence = computed(() => {
            const total = appData.membres.reduce((sum, m) => sum + m.presence, 0);
            return Math.round(total / appData.membres.length);
        });

        const tauxRecouvrement = computed(() => {
            const totalAttendu = appData.membres.length * 100000; // Montant attendu par membre
            const totalRecu = appData.statistiques.cotisationsTotales;
            return Math.round((totalRecu / totalAttendu) * 100);
        });

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        };

        return {
            appData,
            moyennePresence,
            tauxRecouvrement,
            formatDate
        };
    }
};

// Composant Membres
const MembresComponent = {
    template: `
        <div>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">👥 Gestion des Membres</h2>
                <button class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transition-all duration-300">
                    + Ajouter un membre
                </button>
            </div>

            <!-- Tableau des membres moderne -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cotisations</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Présence</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membre in appData.membres" :key="membre.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ membre.prenom }} {{ membre.nom }}</div>
                                            <div class="text-sm text-gray-500">{{ membre.telephone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        {{ membre.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-2 py-1 text-xs font-medium rounded-full',
                                                membre.statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                                        {{ membre.statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatMoney(membre.cotisations) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" :style="{width: membre.presence + '%'}"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ membre.presence }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900">Modifier</button>
                                        <button class="text-green-600 hover:text-green-900">Profil</button>
                                        <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `,
    setup() {
        const formatMoney = (amount) => {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'XOF'
            }).format(amount);
        };

        return {
            appData,
            formatMoney
        };
    }
};

// Composants temporaires pour les autres sections
const CotisationsComponent = {
    template: `<div><h2 class="text-3xl font-bold text-gray-900 mb-8">💰 Gestion des Cotisations</h2><p class="text-gray-600">Section en développement...</p></div>`
};

const ActivitesComponent = {
    template: `<div><h2 class="text-3xl font-bold text-gray-900 mb-8">📅 Gestion des Activités</h2><p class="text-gray-600">Section en développement...</p></div>`
};

const EvenementsComponent = {
    template: `<div><h2 class="text-3xl font-bold text-gray-900 mb-8">🎤 Gestion des Événements</h2><p class="text-gray-600">Section en développement...</p></div>`
};

// Application principale
const app = createApp(Dashboard);
app.mount('#app');

console.log('🚀 Gestion Kourel - Application Vue.js moderne initialisée');
console.log('📊 Dashboard interactif avec statistiques en temps réel');
console.log('🎨 Interface glassmorphism et animations fluides');
console.log('📱 Design responsive et moderne');
