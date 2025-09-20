<template>
  <div class="space-y-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Activités</h1>
        <p class="text-gray-600 mt-2">Planifiez et suivez les répétitions et activités</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern" @click="ouvrirModalActivite">
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouvelle activité
        </button>
      </div>
    </div>

    <!-- Statistiques des activités -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Activités cette semaine</p>
            <p class="kpi-value">{{ statistiques.activitesCetteSemaine }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +2 cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <CalendarDaysIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Taux de Présence Moyen</p>
            <p class="kpi-value">{{ statistiques.tauxPresenceMoyen }}%</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +3% cette semaine
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
            <p class="text-sm font-medium text-gray-600">Membres Présents</p>
            <p class="kpi-value">{{ statistiques.membresPresents }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +5 aujourd'hui
            </div>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <UsersIcon class="w-6 h-6 text-purple-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Retards</p>
            <p class="kpi-value">{{ statistiques.retards }}</p>
            <div class="kpi-change negative">
              <ArrowDownIcon class="w-4 h-4" />
              -2 cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
            <ClockIcon class="w-6 h-6 text-orange-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Vue calendrier et liste -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Calendrier -->
      <div class="lg:col-span-2">
        <div class="card-modern">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Calendrier des Activités</h3>
              <div class="flex items-center space-x-2">
                <button 
                  @click="moisPrecedent"
                  class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <ChevronLeftIcon class="w-5 h-5" />
                </button>
                <span class="text-sm font-medium text-gray-700">{{ moisCourant }}</span>
                <button 
                  @click="moisSuivant"
                  class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <ChevronRightIcon class="w-5 h-5" />
                </button>
              </div>
            </div>

            <!-- Grille du calendrier -->
            <div class="grid grid-cols-7 gap-1 mb-4">
              <div v-for="jour in joursSemaine" :key="jour" class="text-center text-sm font-medium text-gray-500 py-2">
                {{ jour }}
              </div>
            </div>

            <div class="grid grid-cols-7 gap-1">
              <div 
                v-for="jour in joursDuMois" 
                :key="jour.date"
                :class="[
                  'min-h-[80px] p-2 border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors',
                  jour.estAujourdhui ? 'bg-blue-50 border-blue-300' : '',
                  jour.estAutreMois ? 'text-gray-400' : 'text-gray-900'
                ]"
                @click="selectionnerJour(jour)"
              >
                <div class="text-sm font-medium mb-1">{{ jour.numero }}</div>
                <div class="space-y-1">
                  <div 
                    v-for="activite in jour.activites" 
                    :key="activite.id"
                    :class="[
                      'text-xs px-2 py-1 rounded-full text-white truncate',
                      getTypeActiviteColor(activite.type)
                    ]"
                    :title="activite.nom"
                  >
                    {{ activite.nom }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Liste des activités -->
      <div class="space-y-6">
        <!-- Activités à venir -->
        <div class="card-modern">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activités à Venir</h3>
            <div class="space-y-3">
              <div 
                v-for="activite in activitesAVenir" 
                :key="activite.id"
                class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                @click="voirDetailsActivite(activite)"
              >
                <div :class="getTypeActiviteIconClass(activite.type)" class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                  <component :is="getTypeActiviteIcon(activite.type)" class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-900 truncate">{{ activite.nom }}</p>
                  <p class="text-sm text-gray-600">{{ formatDateTime(activite.date_debut) }}</p>
                  <p class="text-xs text-gray-500">{{ activite.lieu || 'Lieu non spécifié' }}</p>
                </div>
                <span :class="getStatutActiviteBadgeClass(activite.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ activite.statut }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Activités récentes -->
        <div class="card-modern">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activités Récentes</h3>
            <div class="space-y-3">
              <div 
                v-for="activite in activitesRecentes" 
                :key="activite.id"
                class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                @click="voirDetailsActivite(activite)"
              >
                <div :class="getTypeActiviteIconClass(activite.type)" class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                  <component :is="getTypeActiviteIcon(activite.type)" class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-900 truncate">{{ activite.nom }}</p>
                  <p class="text-sm text-gray-600">{{ formatDateTime(activite.date_debut) }}</p>
                  <div class="flex items-center space-x-2 mt-1">
                    <span class="text-xs text-gray-500">{{ activite.presences_count }} présences</span>
                    <span class="text-xs text-green-600">{{ activite.taux_presence }}%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de création/édition d'activité -->
    <div v-if="modalActiviteOuvert" class="modal-overlay" @click="fermerModalActivite">
      <div class="modal-content max-w-2xl" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          {{ activiteEnEdition ? 'Modifier l\'activité' : 'Nouvelle activité' }}
        </h3>
        
        <form @submit.prevent="sauvegarderActivite" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type d'activité *</label>
              <select 
                v-model="formActivite.type"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="repetition">Répétition</option>
                <option value="prestation">Prestation</option>
                <option value="goudi_aldiouma">Goudi Aldiouma</option>
                <option value="formation">Formation</option>
                <option value="reunion">Réunion</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
              <select 
                v-model="formActivite.statut"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="planifie">Planifié</option>
                <option value="confirme">Confirmé</option>
                <option value="en_cours">En cours</option>
                <option value="termine">Terminé</option>
                <option value="annule">Annulé</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'activité *</label>
            <input 
              v-model="formActivite.nom"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formActivite.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure de début *</label>
              <input 
                v-model="formActivite.date_debut"
                type="datetime-local" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin *</label>
              <input 
                v-model="formActivite.date_fin"
                type="datetime-local" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
            <input 
              v-model="formActivite.lieu"
              type="text" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Responsable</label>
            <select 
              v-model="formActivite.responsable_id"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Sélectionner un responsable</option>
              <option v-for="membre in membres" :key="membre.id" :value="membre.id">
                {{ membre.nom_complet }}
              </option>
            </select>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModalActivite"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              {{ activiteEnEdition ? 'Modifier' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de détails d'activité -->
    <div v-if="modalDetailsOuvert" class="modal-overlay" @click="fermerModalDetails">
      <div class="modal-content max-w-4xl" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">{{ activiteSelectionnee?.nom }}</h3>
          <button 
            @click="fermerModalDetails"
            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
          >
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <div v-if="activiteSelectionnee" class="space-y-6">
          <!-- Informations générales -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card-modern p-4">
              <h4 class="font-semibold text-gray-900 mb-3">Informations</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-medium">{{ activiteSelectionnee.type }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Statut:</span>
                  <span :class="getStatutActiviteBadgeClass(activiteSelectionnee.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ activiteSelectionnee.statut }}
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Début:</span>
                  <span class="font-medium">{{ formatDateTime(activiteSelectionnee.date_debut) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Fin:</span>
                  <span class="font-medium">{{ formatDateTime(activiteSelectionnee.date_fin) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Lieu:</span>
                  <span class="font-medium">{{ activiteSelectionnee.lieu || 'Non spécifié' }}</span>
                </div>
              </div>
            </div>

            <div class="card-modern p-4">
              <h4 class="font-semibold text-gray-900 mb-3">Statistiques</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total présences:</span>
                  <span class="font-medium">{{ activiteSelectionnee.presences_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Présents:</span>
                  <span class="font-medium text-green-600">{{ activiteSelectionnee.presents_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Absents:</span>
                  <span class="font-medium text-red-600">{{ activiteSelectionnee.absents_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Retards:</span>
                  <span class="font-medium text-orange-600">{{ activiteSelectionnee.retards_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Taux de présence:</span>
                  <span class="font-medium">{{ activiteSelectionnee.taux_presence || 0 }}%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Liste des présences -->
          <div class="card-modern">
            <div class="p-6">
              <h4 class="font-semibold text-gray-900 mb-4">Liste des Présences</h4>
              <div class="overflow-x-auto">
                <table class="table-modern w-full">
                  <thead>
                    <tr>
                      <th class="text-left">Membre</th>
                      <th class="text-left">Statut</th>
                      <th class="text-left">Heure d'arrivée</th>
                      <th class="text-left">Retard</th>
                      <th class="text-left">Justification</th>
                      <th class="text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="presence in presencesActivite" :key="presence.id" class="hover:bg-gray-50">
                      <td class="py-4">
                        <div class="flex items-center space-x-3">
                          <img 
                            :src="presence.membre.photo_url || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
                            :alt="presence.membre.nom_complet"
                            class="w-8 h-8 rounded-full"
                          >
                          <span class="font-medium text-gray-900">{{ presence.membre.nom_complet }}</span>
                        </div>
                      </td>
                      <td class="py-4">
                        <span :class="getStatutPresenceBadgeClass(presence.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                          {{ presence.statut }}
                        </span>
                      </td>
                      <td class="py-4">
                        <span class="text-sm text-gray-600">
                          {{ presence.heure_arrivee ? formatDateTime(presence.heure_arrivee) : '-' }}
                        </span>
                      </td>
                      <td class="py-4">
                        <span v-if="presence.minutes_retard > 0" class="text-sm text-orange-600">
                          {{ presence.minutes_retard }} min
                        </span>
                        <span v-else class="text-sm text-gray-500">-</span>
                      </td>
                      <td class="py-4">
                        <span class="text-sm text-gray-600">
                          {{ presence.justification || '-' }}
                        </span>
                      </td>
                      <td class="py-4">
                        <div class="flex items-center justify-end space-x-2">
                          <button 
                            @click="modifierPresence(presence)"
                            class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                            title="Modifier la présence"
                          >
                            <PencilIcon class="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  PlusIcon,
  CalendarDaysIcon,
  CheckCircleIcon,
  UsersIcon,
  ClockIcon,
  ArrowUpIcon,
  ArrowDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  XMarkIcon,
  PencilIcon,
  MusicalNoteIcon,
  MegaphoneIcon,
  AcademicCapIcon,
  UserGroupIcon
} from '@heroicons/vue/24/outline'

// État réactif
const modalActiviteOuvert = ref(false)
const modalDetailsOuvert = ref(false)
const activiteEnEdition = ref(null)
const activiteSelectionnee = ref(null)
const dateCourante = ref(new Date())

const statistiques = ref({
  activitesCetteSemaine: 8,
  tauxPresenceMoyen: 85.2,
  membresPresents: 45,
  retards: 3
})

const formActivite = ref({
  type: 'repetition',
  nom: '',
  description: '',
  date_debut: '',
  date_fin: '',
  lieu: '',
  responsable_id: '',
  statut: 'planifie'
})

// Données simulées
const membres = ref([
  { id: 1, nom_complet: 'Moussa Fall' },
  { id: 2, nom_complet: 'Aminata Diop' },
  { id: 3, nom_complet: 'Ibrahima Ba' }
])

const activites = ref([
  {
    id: 1,
    type: 'repetition',
    nom: 'Répétition Chorale',
    description: 'Répétition hebdomadaire de la chorale',
    date_debut: '2025-01-15T18:00:00',
    date_fin: '2025-01-15T20:00:00',
    lieu: 'Salle de répétition',
    responsable_id: 1,
    statut: 'confirme',
    presences_count: 25,
    presents_count: 22,
    absents_count: 2,
    retards_count: 1,
    taux_presence: 88
  },
  {
    id: 2,
    type: 'goudi_aldiouma',
    nom: 'Goudi Aldiouma',
    description: 'Cérémonie religieuse hebdomadaire',
    date_debut: '2025-01-16T19:30:00',
    date_fin: '2025-01-16T21:30:00',
    lieu: 'Mosquée principale',
    responsable_id: 2,
    statut: 'planifie',
    presences_count: 0,
    presents_count: 0,
    absents_count: 0,
    retards_count: 0,
    taux_presence: 0
  },
  {
    id: 3,
    type: 'formation',
    nom: 'Formation Déclamation',
    description: 'Formation à la déclamation coranique',
    date_debut: '2025-01-17T16:00:00',
    date_fin: '2025-01-17T18:00:00',
    lieu: 'Salle de formation',
    responsable_id: 3,
    statut: 'en_cours',
    presences_count: 15,
    presents_count: 14,
    absents_count: 1,
    retards_count: 0,
    taux_presence: 93
  }
])

const presencesActivite = ref([
  {
    id: 1,
    membre: { nom_complet: 'Moussa Fall', photo_url: null },
    statut: 'present',
    heure_arrivee: '2025-01-15T18:05:00',
    minutes_retard: 5,
    justification: null
  },
  {
    id: 2,
    membre: { nom_complet: 'Aminata Diop', photo_url: null },
    statut: 'absent_justifie',
    heure_arrivee: null,
    minutes_retard: 0,
    justification: 'Maladie'
  },
  {
    id: 3,
    membre: { nom_complet: 'Ibrahima Ba', photo_url: null },
    statut: 'retard',
    heure_arrivee: '2025-01-15T18:15:00',
    minutes_retard: 15,
    justification: 'Trafic'
  }
])

// Computed properties
const moisCourant = computed(() => {
  return dateCourante.value.toLocaleDateString('fr-FR', { 
    year: 'numeric', 
    month: 'long' 
  })
})

const joursSemaine = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']

const joursDuMois = computed(() => {
  const annee = dateCourante.value.getFullYear()
  const mois = dateCourante.value.getMonth()
  const premierJour = new Date(annee, mois, 1)
  const dernierJour = new Date(annee, mois + 1, 0)
  const premierJourSemaine = premierJour.getDay() === 0 ? 6 : premierJour.getDay() - 1
  
  const jours = []
  
  // Jours du mois précédent
  for (let i = premierJourSemaine - 1; i >= 0; i--) {
    const jour = new Date(annee, mois, -i)
    jours.push({
      date: jour.toISOString().split('T')[0],
      numero: jour.getDate(),
      estAutreMois: true,
      estAujourdhui: false,
      activites: []
    })
  }
  
  // Jours du mois courant
  for (let jour = 1; jour <= dernierJour.getDate(); jour++) {
    const date = new Date(annee, mois, jour)
    const estAujourdhui = date.toDateString() === new Date().toDateString()
    const activitesDuJour = activites.value.filter(activite => {
      const dateActivite = new Date(activite.date_debut)
      return dateActivite.toDateString() === date.toDateString()
    })
    
    jours.push({
      date: date.toISOString().split('T')[0],
      numero: jour,
      estAutreMois: false,
      estAujourdhui,
      activites: activitesDuJour
    })
  }
  
  // Jours du mois suivant
  const joursRestants = 42 - jours.length
  for (let jour = 1; jour <= joursRestants; jour++) {
    const date = new Date(annee, mois + 1, jour)
    jours.push({
      date: date.toISOString().split('T')[0],
      numero: jour,
      estAutreMois: true,
      estAujourdhui: false,
      activites: []
    })
  }
  
  return jours
})

const activitesAVenir = computed(() => {
  const maintenant = new Date()
  return activites.value
    .filter(activite => new Date(activite.date_debut) > maintenant)
    .sort((a, b) => new Date(a.date_debut) - new Date(b.date_debut))
    .slice(0, 5)
})

const activitesRecentes = computed(() => {
  const maintenant = new Date()
  return activites.value
    .filter(activite => new Date(activite.date_debut) <= maintenant)
    .sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut))
    .slice(0, 5)
})

// Fonctions utilitaires
const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getTypeActiviteColor = (type) => {
  const colors = {
    'repetition': 'bg-blue-500',
    'prestation': 'bg-purple-500',
    'goudi_aldiouma': 'bg-green-500',
    'formation': 'bg-orange-500',
    'reunion': 'bg-gray-500'
  }
  return colors[type] || 'bg-gray-500'
}

const getTypeActiviteIcon = (type) => {
  const icons = {
    'repetition': MusicalNoteIcon,
    'prestation': MegaphoneIcon,
    'goudi_aldiouma': UserGroupIcon,
    'formation': AcademicCapIcon,
    'reunion': UserGroupIcon
  }
  return icons[type] || UserGroupIcon
}

const getTypeActiviteIconClass = (type) => {
  const classes = {
    'repetition': 'bg-blue-100 text-blue-600',
    'prestation': 'bg-purple-100 text-purple-600',
    'goudi_aldiouma': 'bg-green-100 text-green-600',
    'formation': 'bg-orange-100 text-orange-600',
    'reunion': 'bg-gray-100 text-gray-600'
  }
  return classes[type] || 'bg-gray-100 text-gray-600'
}

const getStatutActiviteBadgeClass = (statut) => {
  const classes = {
    'planifie': 'bg-gray-100 text-gray-800',
    'confirme': 'bg-blue-100 text-blue-800',
    'en_cours': 'bg-green-100 text-green-800',
    'termine': 'bg-gray-100 text-gray-800',
    'annule': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getStatutPresenceBadgeClass = (statut) => {
  const classes = {
    'present': 'bg-green-100 text-green-800',
    'absent_justifie': 'bg-yellow-100 text-yellow-800',
    'absent_non_justifie': 'bg-red-100 text-red-800',
    'retard': 'bg-orange-100 text-orange-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

// Fonctions de gestion
const ouvrirModalActivite = () => {
  activiteEnEdition.value = null
  formActivite.value = {
    type: 'repetition',
    nom: '',
    description: '',
    date_debut: '',
    date_fin: '',
    lieu: '',
    responsable_id: '',
    statut: 'planifie'
  }
  modalActiviteOuvert.value = true
}

const fermerModalActivite = () => {
  modalActiviteOuvert.value = false
  activiteEnEdition.value = null
}

const sauvegarderActivite = () => {
  if (activiteEnEdition.value) {
    // Modification
    const index = activites.value.findIndex(a => a.id === activiteEnEdition.value.id)
    if (index !== -1) {
      activites.value[index] = { ...activites.value[index], ...formActivite.value }
    }
  } else {
    // Ajout
    const nouvelleActivite = {
      id: Date.now(),
      ...formActivite.value,
      presences_count: 0,
      presents_count: 0,
      absents_count: 0,
      retards_count: 0,
      taux_presence: 0
    }
    activites.value.push(nouvelleActivite)
  }
  
  fermerModalActivite()
  window.addToast('Activité sauvegardée avec succès !', 'success')
}

const voirDetailsActivite = (activite) => {
  activiteSelectionnee.value = activite
  modalDetailsOuvert.value = true
}

const fermerModalDetails = () => {
  modalDetailsOuvert.value = false
  activiteSelectionnee.value = null
}

const selectionnerJour = (jour) => {
  if (jour.activites.length > 0) {
    voirDetailsActivite(jour.activites[0])
  }
}

const moisPrecedent = () => {
  dateCourante.value = new Date(dateCourante.value.getFullYear(), dateCourante.value.getMonth() - 1, 1)
}

const moisSuivant = () => {
  dateCourante.value = new Date(dateCourante.value.getFullYear(), dateCourante.value.getMonth() + 1, 1)
}

const modifierPresence = (presence) => {
  window.addToast(`Modification de la présence de ${presence.membre.nom_complet}`, 'info')
}

// Initialisation
onMounted(() => {
  console.log('Composant Activités initialisé')
})
</script>
