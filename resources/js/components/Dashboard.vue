<template>
  <div class="space-y-8">
    <!-- En-tête du dashboard -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble de votre dahira/kourel</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern">
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouvelle activité
        </button>
      </div>
    </div>

    <!-- KPIs principaux -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Total Membres</p>
            <p class="kpi-value">{{ kpis.totalMembres }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +12% ce mois
            </div>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <UsersIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Taux de Présence</p>
            <p class="kpi-value">{{ kpis.tauxPresence }}%</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +5% cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <CheckCircleIcon class="w-6 h-6 text-green-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Cotisations Collectées</p>
            <p class="kpi-value">{{ formatCurrency(kpis.cotisationsCollectees) }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +8% ce mois
            </div>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <CurrencyDollarIcon class="w-6 h-6 text-yellow-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Alertes Actives</p>
            <p class="kpi-value">{{ kpis.alertesActives }}</p>
            <div class="kpi-change negative">
              <ArrowDownIcon class="w-4 h-4" />
              -3 cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <ExclamationTriangleIcon class="w-6 h-6 text-red-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Graphiques et statistiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Graphique des présences -->
      <div class="card-modern">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des Présences</h3>
          <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
              <ChartBarIcon class="w-12 h-12 text-gray-400 mx-auto mb-2" />
              <p class="text-gray-500">Graphique des présences</p>
              <p class="text-sm text-gray-400">Intégration Chart.js en cours</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Activités récentes -->
      <div class="card-modern">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Activités Récentes</h3>
          <div class="space-y-4">
            <div v-for="activite in activitesRecentes" :key="activite.id" class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <CalendarDaysIcon class="w-5 h-5 text-blue-600" />
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ activite.nom }}</p>
                <p class="text-sm text-gray-600">{{ activite.type }} • {{ formatDate(activite.date_debut) }}</p>
              </div>
              <div class="text-right">
                <span :class="getStatutBadgeClass(activite.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ activite.statut }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Membres récents et alertes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Nouveaux membres -->
      <div class="card-modern">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Nouveaux Membres</h3>
          <div class="space-y-3">
            <div v-for="membre in nouveauxMembres" :key="membre.id" class="flex items-center space-x-3">
              <img 
                :src="membre.photo_url || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
                :alt="membre.nom_complet"
                class="w-10 h-10 rounded-full"
              >
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ membre.nom_complet }}</p>
                <p class="text-sm text-gray-600">{{ membre.profession || 'Membre' }}</p>
              </div>
              <span class="text-xs text-gray-500">{{ formatDate(membre.date_adhesion) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Alertes récentes -->
      <div class="card-modern">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Alertes Récentes</h3>
          <div class="space-y-3">
            <div v-for="alerte in alertesRecentes" :key="alerte.id" class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
              <div :class="getAlerteIconClass(alerte.niveau_urgence)" class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <component :is="getAlerteIcon(alerte.niveau_urgence)" class="w-4 h-4" />
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ alerte.titre }}</p>
                <p class="text-sm text-gray-600">{{ alerte.message }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ formatDate(alerte.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  PlusIcon,
  UsersIcon,
  CheckCircleIcon,
  CurrencyDollarIcon,
  ExclamationTriangleIcon,
  ArrowUpIcon,
  ArrowDownIcon,
  ChartBarIcon,
  CalendarDaysIcon,
  BellIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'

// État réactif
const kpis = ref({
  totalMembres: 156,
  tauxPresence: 87.5,
  cotisationsCollectees: 2450000,
  alertesActives: 7
})

const activitesRecentes = ref([
  {
    id: 1,
    nom: 'Répétition Chorale',
    type: 'Répétition',
    date_debut: '2025-01-15T18:00:00',
    statut: 'confirme'
  },
  {
    id: 2,
    nom: 'Goudi Aldiouma',
    type: 'Goudi Aldiouma',
    date_debut: '2025-01-16T19:30:00',
    statut: 'planifie'
  },
  {
    id: 3,
    nom: 'Formation Déclamation',
    type: 'Formation',
    date_debut: '2025-01-17T16:00:00',
    statut: 'en_cours'
  }
])

const nouveauxMembres = ref([
  {
    id: 1,
    nom: 'Fall',
    prenom: 'Moussa',
    photo_url: null,
    profession: 'Ingénieur',
    date_adhesion: '2025-01-10'
  },
  {
    id: 2,
    nom: 'Diop',
    prenom: 'Aminata',
    photo_url: null,
    profession: 'Enseignante',
    date_adhesion: '2025-01-12'
  },
  {
    id: 3,
    nom: 'Ba',
    prenom: 'Ibrahima',
    photo_url: null,
    profession: 'Médecin',
    date_adhesion: '2025-01-14'
  }
])

const alertesRecentes = ref([
  {
    id: 1,
    titre: 'Absence répétitive',
    message: 'Moussa Fall a 3 absences consécutives',
    niveau_urgence: 'warning',
    created_at: '2025-01-15T10:30:00'
  },
  {
    id: 2,
    titre: 'Cotisation en retard',
    message: '5 membres ont des cotisations en retard',
    niveau_urgence: 'error',
    created_at: '2025-01-15T09:15:00'
  },
  {
    id: 3,
    titre: 'Événement majeur',
    message: 'Préparation du Magal dans 2 semaines',
    niveau_urgence: 'info',
    created_at: '2025-01-14T16:45:00'
  }
])

// Fonctions utilitaires
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(amount)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const getStatutBadgeClass = (statut) => {
  const classes = {
    'planifie': 'bg-gray-100 text-gray-800',
    'confirme': 'bg-blue-100 text-blue-800',
    'en_cours': 'bg-green-100 text-green-800',
    'termine': 'bg-gray-100 text-gray-800',
    'annule': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getAlerteIcon = (niveau) => {
  switch (niveau) {
    case 'critical': return XCircleIcon
    case 'error': return XCircleIcon
    case 'warning': return ExclamationTriangleIcon
    case 'info': return BellIcon
    default: return BellIcon
  }
}

const getAlerteIconClass = (niveau) => {
  const classes = {
    'critical': 'bg-red-100 text-red-600',
    'error': 'bg-red-100 text-red-600',
    'warning': 'bg-yellow-100 text-yellow-600',
    'info': 'bg-blue-100 text-blue-600'
  }
  return classes[niveau] || 'bg-gray-100 text-gray-600'
}

// Ajouter les propriétés calculées
const membresAvecNomComplet = ref(
  nouveauxMembres.value.map(membre => ({
    ...membre,
    nom_complet: `${membre.prenom} ${membre.nom}`
  }))
)

// Initialisation
onMounted(() => {
  // Simuler le chargement des données
  console.log('Dashboard initialisé')
})
</script>

