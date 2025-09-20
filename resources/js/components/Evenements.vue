<template>
  <div class="space-y-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Événements</h1>
        <p class="text-gray-600 mt-2">Organisez les événements spéciaux (Magal, Gamou, Promokhane...)</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern" @click="ouvrirModalEvenement">
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouvel événement
        </button>
      </div>
    </div>

    <!-- Statistiques des événements -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Événements cette année</p>
            <p class="kpi-value">{{ statistiques.evenementsCetteAnnee }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +3 cette année
            </div>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <CalendarDaysIcon class="w-6 h-6 text-purple-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">À venir</p>
            <p class="kpi-value">{{ statistiques.aVenir }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +1 ce mois
            </div>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <ClockIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Budget total</p>
            <p class="kpi-value">{{ formatCurrency(statistiques.budgetTotal) }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +12% cette année
            </div>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <CurrencyDollarIcon class="w-6 h-6 text-green-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Participants</p>
            <p class="kpi-value">{{ statistiques.totalParticipants }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +25 ce mois
            </div>
          </div>
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
            <UsersIcon class="w-6 h-6 text-orange-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card-modern p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement</label>
          <select 
            v-model="filtres.type"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les types</option>
            <option value="magal">Magal</option>
            <option value="gamou">Gamou</option>
            <option value="promokhane">Promokhane</option>
            <option value="conference">Conférence</option>
            <option value="formation">Formation</option>
            <option value="autre">Autre</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
          <select 
            v-model="filtres.statut"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les statuts</option>
            <option value="planifie">Planifié</option>
            <option value="confirme">Confirmé</option>
            <option value="en_cours">En cours</option>
            <option value="termine">Terminé</option>
            <option value="annule">Annulé</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
          <select 
            v-model="filtres.periode"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Toutes les périodes</option>
            <option value="a_venir">À venir</option>
            <option value="en_cours">En cours</option>
            <option value="termines">Terminés</option>
            <option value="cette_annee">Cette année</option>
          </select>
        </div>
        <div class="flex items-end">
          <button 
            @click="reinitialiserFiltres"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Réinitialiser
          </button>
        </div>
      </div>
    </div>

    <!-- Liste des événements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
      <div 
        v-for="evenement in evenementsFiltres" 
        :key="evenement.id"
        class="card-modern p-6 hover-lift cursor-pointer"
        @click="voirDetailsEvenement(evenement)"
      >
        <!-- En-tête de la carte -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ evenement.nom }}</h3>
            <p class="text-sm text-gray-600">{{ evenement.type_francais }}</p>
          </div>
          <span :class="getStatutEvenementBadgeClass(evenement.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
            {{ evenement.statut_francais }}
          </span>
        </div>

        <!-- Informations principales -->
        <div class="space-y-3 mb-4">
          <div class="flex items-center space-x-2">
            <CalendarDaysIcon class="w-4 h-4 text-gray-400" />
            <span class="text-sm text-gray-600">{{ formatDateTime(evenement.date_debut) }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <MapPinIcon class="w-4 h-4 text-gray-400" />
            <span class="text-sm text-gray-600">{{ evenement.lieu || 'Lieu non spécifié' }}</span>
          </div>
          <div v-if="evenement.budget" class="flex items-center space-x-2">
            <CurrencyDollarIcon class="w-4 h-4 text-gray-400" />
            <span class="text-sm text-gray-600">{{ formatCurrency(evenement.budget) }}</span>
          </div>
        </div>

        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ evenement.description || 'Aucune description' }}</p>

        <!-- Participants -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
          <div class="flex items-center space-x-2">
            <UsersIcon class="w-4 h-4 text-gray-400" />
            <span class="text-sm text-gray-600">{{ evenement.nombre_total_membres }} participants</span>
          </div>
          <div class="flex space-x-2">
            <button 
              @click.stop="editerEvenement(evenement)"
              class="p-2 text-gray-400 hover:text-green-600 transition-colors"
              title="Éditer"
            >
              <PencilIcon class="w-4 h-4" />
            </button>
            <button 
              @click.stop="gererParticipants(evenement)"
              class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
              title="Gérer les participants"
            >
              <UserGroupIcon class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-center space-x-4">
      <button 
        @click="pagePrecedente"
        :disabled="pageCourante === 1"
        class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Précédent
      </button>
      <span class="text-sm text-gray-700">
        Page {{ pageCourante }} sur {{ totalPages }}
      </span>
      <button 
        @click="pageSuivante"
        :disabled="pageCourante === totalPages"
        class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Suivant
      </button>
    </div>

    <!-- Modal de création/édition d'événement -->
    <div v-if="modalEvenementOuvert" class="modal-overlay" @click="fermerModalEvenement">
      <div class="modal-content max-w-2xl" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          {{ evenementEnEdition ? 'Modifier l\'événement' : 'Nouvel événement' }}
        </h3>
        
        <form @submit.prevent="sauvegarderEvenement" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement *</label>
              <select 
                v-model="formEvenement.type"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="magal">Magal</option>
                <option value="gamou">Gamou</option>
                <option value="promokhane">Promokhane</option>
                <option value="conference">Conférence</option>
                <option value="formation">Formation</option>
                <option value="autre">Autre</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
              <select 
                v-model="formEvenement.statut"
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'événement *</label>
            <input 
              v-model="formEvenement.nom"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formEvenement.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure de début *</label>
              <input 
                v-model="formEvenement.date_debut"
                type="datetime-local" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin *</label>
              <input 
                v-model="formEvenement.date_fin"
                type="datetime-local" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
              <input 
                v-model="formEvenement.lieu"
                type="text" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Budget (FCFA)</label>
              <input 
                v-model="formEvenement.budget"
                type="number" 
                min="0"
                step="1000"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModalEvenement"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              {{ evenementEnEdition ? 'Modifier' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de détails d'événement -->
    <div v-if="modalDetailsOuvert" class="modal-overlay" @click="fermerModalDetails">
      <div class="modal-content max-w-4xl" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">{{ evenementSelectionne?.nom }}</h3>
          <button 
            @click="fermerModalDetails"
            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
          >
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <div v-if="evenementSelectionne" class="space-y-6">
          <!-- Informations générales -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card-modern p-4">
              <h4 class="font-semibold text-gray-900 mb-3">Informations</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-medium">{{ evenementSelectionne.type_francais }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Statut:</span>
                  <span :class="getStatutEvenementBadgeClass(evenementSelectionne.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ evenementSelectionne.statut_francais }}
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Début:</span>
                  <span class="font-medium">{{ formatDateTime(evenementSelectionne.date_debut) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Fin:</span>
                  <span class="font-medium">{{ formatDateTime(evenementSelectionne.date_fin) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Lieu:</span>
                  <span class="font-medium">{{ evenementSelectionne.lieu || 'Non spécifié' }}</span>
                </div>
                <div v-if="evenementSelectionne.budget" class="flex justify-between">
                  <span class="text-gray-600">Budget:</span>
                  <span class="font-medium">{{ formatCurrency(evenementSelectionne.budget) }}</span>
                </div>
              </div>
            </div>

            <div class="card-modern p-4">
              <h4 class="font-semibold text-gray-900 mb-3">Participants</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total participants:</span>
                  <span class="font-medium">{{ evenementSelectionne.nombre_total_membres }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Déclamation:</span>
                  <span class="font-medium">{{ evenementSelectionne.membres_selectionnes?.declamation?.length || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Chorale:</span>
                  <span class="font-medium">{{ evenementSelectionne.membres_selectionnes?.chorale?.length || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Animation:</span>
                  <span class="font-medium">{{ evenementSelectionne.membres_selectionnes?.animation?.length || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Organisation:</span>
                  <span class="font-medium">{{ evenementSelectionne.membres_selectionnes?.organisation?.length || 0 }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div v-if="evenementSelectionne.description" class="card-modern p-4">
            <h4 class="font-semibold text-gray-900 mb-3">Description</h4>
            <p class="text-sm text-gray-600">{{ evenementSelectionne.description }}</p>
          </div>

          <!-- Configuration -->
          <div v-if="evenementSelectionne.configuration" class="card-modern p-4">
            <h4 class="font-semibold text-gray-900 mb-3">Configuration</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div v-for="(value, key) in evenementSelectionne.configuration" :key="key" class="flex justify-between">
                <span class="text-gray-600 capitalize">{{ key.replace('_', ' ') }}:</span>
                <span class="font-medium">{{ value }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de gestion des participants -->
    <div v-if="modalParticipantsOuvert" class="modal-overlay" @click="fermerModalParticipants">
      <div class="modal-content max-w-4xl" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">
            Gérer les participants - {{ evenementSelectionne?.nom }}
          </h3>
          <button 
            @click="fermerModalParticipants"
            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
          >
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <div v-if="evenementSelectionne" class="space-y-6">
          <!-- Onglets des prestations -->
          <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
              <button 
                v-for="prestation in prestationsDisponibles" 
                :key="prestation.id"
                @click="prestationActive = prestation.id"
                :class="[
                  'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                  prestationActive === prestation.id 
                    ? 'border-blue-500 text-blue-600' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                {{ prestation.label }}
              </button>
            </nav>
          </div>

          <!-- Contenu des prestations -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <h4 class="font-semibold text-gray-900">{{ getPrestationActive().label }}</h4>
              <button 
                @click="ajouterParticipant"
                class="btn-modern"
              >
                <PlusIcon class="w-4 h-4 mr-2" />
                Ajouter un participant
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div 
                v-for="membre in membresDisponibles" 
                :key="membre.id"
                :class="[
                  'p-4 border rounded-lg cursor-pointer transition-colors',
                  estParticipant(membre.id) 
                    ? 'border-green-300 bg-green-50' 
                    : 'border-gray-200 hover:border-gray-300'
                ]"
                @click="toggleParticipant(membre.id)"
              >
                <div class="flex items-center space-x-3">
                  <img 
                    :src="membre.photo_url || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
                    :alt="membre.nom_complet"
                    class="w-10 h-10 rounded-full"
                  >
                  <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ membre.nom_complet }}</p>
                    <p class="text-sm text-gray-600">{{ membre.profession || 'Membre' }}</p>
                  </div>
                  <div v-if="estParticipant(membre.id)" class="text-green-600">
                    <CheckIcon class="w-5 h-5" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <button 
              @click="fermerModalParticipants"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Fermer
            </button>
            <button 
              @click="sauvegarderParticipants"
              class="btn-modern"
            >
              Sauvegarder
            </button>
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
  ClockIcon,
  CurrencyDollarIcon,
  UsersIcon,
  ArrowUpIcon,
  PencilIcon,
  UserGroupIcon,
  MapPinIcon,
  XMarkIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

// État réactif
const modalEvenementOuvert = ref(false)
const modalDetailsOuvert = ref(false)
const modalParticipantsOuvert = ref(false)
const evenementEnEdition = ref(null)
const evenementSelectionne = ref(null)
const prestationActive = ref('declamation')
const pageCourante = ref(1)
const evenementsParPage = 6

const filtres = ref({
  type: '',
  statut: '',
  periode: ''
})

const statistiques = ref({
  evenementsCetteAnnee: 12,
  aVenir: 4,
  budgetTotal: 25000000,
  totalParticipants: 156
})

const formEvenement = ref({
  type: 'magal',
  nom: '',
  description: '',
  date_debut: '',
  date_fin: '',
  lieu: '',
  budget: '',
  statut: 'planifie'
})

// Données simulées
const membres = ref([
  { id: 1, nom_complet: 'Moussa Fall', profession: 'Ingénieur', photo_url: null },
  { id: 2, nom_complet: 'Aminata Diop', profession: 'Enseignante', photo_url: null },
  { id: 3, nom_complet: 'Ibrahima Ba', profession: 'Médecin', photo_url: null },
  { id: 4, nom_complet: 'Fatou Sall', profession: 'Avocate', photo_url: null },
  { id: 5, nom_complet: 'Cheikh Ndiaye', profession: 'Imam', photo_url: null }
])

const evenements = ref([
  {
    id: 1,
    type: 'magal',
    type_francais: 'Magal',
    nom: 'Magal de Touba 2025',
    description: 'Célébration annuelle du Magal de Touba avec participation de notre dahira',
    date_debut: '2025-02-15T08:00:00',
    date_fin: '2025-02-17T18:00:00',
    lieu: 'Touba, Sénégal',
    budget: 5000000,
    statut: 'planifie',
    statut_francais: 'Planifié',
    membres_selectionnes: {
      declamation: [1, 5],
      chorale: [2, 3, 4],
      animation: [1, 2],
      organisation: [3, 4, 5]
    },
    configuration: {
      dress_code: 'Tenue traditionnelle blanche',
      transport: 'Bus organisé',
      hebergement: 'Hôtel réservé'
    },
    nombre_total_membres: 8
  },
  {
    id: 2,
    type: 'gamou',
    type_francais: 'Gamou',
    nom: 'Gamou de Tivaouane',
    description: 'Participation au Gamou de Tivaouane',
    date_debut: '2025-03-20T10:00:00',
    date_fin: '2025-03-22T16:00:00',
    lieu: 'Tivaouane, Sénégal',
    budget: 3000000,
    statut: 'confirme',
    statut_francais: 'Confirmé',
    membres_selectionnes: {
      declamation: [1, 5],
      chorale: [2, 3],
      animation: [1, 2, 4]
    },
    configuration: {
      dress_code: 'Tenue traditionnelle',
      transport: 'Voitures personnelles'
    },
    nombre_total_membres: 6
  },
  {
    id: 3,
    type: 'promokhane',
    type_francais: 'Promokhane',
    nom: 'Promokhane Annuel',
    description: 'Promokhane annuel de notre dahira',
    date_debut: '2025-04-10T19:00:00',
    date_fin: '2025-04-10T23:00:00',
    lieu: 'Salle des fêtes municipale',
    budget: 1500000,
    statut: 'termine',
    statut_francais: 'Terminé',
    membres_selectionnes: {
      declamation: [1, 2, 5],
      chorale: [2, 3, 4],
      animation: [1, 2, 3, 4, 5]
    },
    configuration: {
      dress_code: 'Tenue de soirée',
      repas: 'Buffet organisé'
    },
    nombre_total_membres: 10
  }
])

const prestationsDisponibles = [
  { id: 'declamation', label: 'Déclamation' },
  { id: 'chorale', label: 'Chorale' },
  { id: 'animation', label: 'Animation' },
  { id: 'organisation', label: 'Organisation' },
  { id: 'logistique', label: 'Logistique' }
]

// Computed properties
const evenementsFiltres = computed(() => {
  let resultat = evenements.value

  // Filtre par type
  if (filtres.value.type) {
    resultat = resultat.filter(evenement => evenement.type === filtres.value.type)
  }

  // Filtre par statut
  if (filtres.value.statut) {
    resultat = resultat.filter(evenement => evenement.statut === filtres.value.statut)
  }

  // Filtre par période
  if (filtres.value.periode) {
    const maintenant = new Date()
    switch (filtres.value.periode) {
      case 'a_venir':
        resultat = resultat.filter(evenement => new Date(evenement.date_debut) > maintenant)
        break
      case 'en_cours':
        resultat = resultat.filter(evenement => {
          const debut = new Date(evenement.date_debut)
          const fin = new Date(evenement.date_fin)
          return maintenant >= debut && maintenant <= fin
        })
        break
      case 'termines':
        resultat = resultat.filter(evenement => new Date(evenement.date_fin) < maintenant)
        break
      case 'cette_annee':
        const anneeCourante = maintenant.getFullYear()
        resultat = resultat.filter(evenement => {
          const anneeEvenement = new Date(evenement.date_debut).getFullYear()
          return anneeEvenement === anneeCourante
        })
        break
    }
  }

  return resultat
})

const totalPages = computed(() => {
  return Math.ceil(evenementsFiltres.value.length / evenementsParPage)
})

const membresDisponibles = computed(() => {
  return membres.value
})

// Fonctions utilitaires
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(amount)
}

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatutEvenementBadgeClass = (statut) => {
  const classes = {
    'planifie': 'bg-gray-100 text-gray-800',
    'confirme': 'bg-blue-100 text-blue-800',
    'en_cours': 'bg-green-100 text-green-800',
    'termine': 'bg-gray-100 text-gray-800',
    'annule': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getPrestationActive = () => {
  return prestationsDisponibles.find(p => p.id === prestationActive.value) || prestationsDisponibles[0]
}

const estParticipant = (membreId) => {
  if (!evenementSelectionne.value?.membres_selectionnes) return false
  const prestation = prestationActive.value
  const participants = evenementSelectionne.value.membres_selectionnes[prestation] || []
  return participants.includes(membreId)
}

// Fonctions de gestion
const ouvrirModalEvenement = () => {
  evenementEnEdition.value = null
  formEvenement.value = {
    type: 'magal',
    nom: '',
    description: '',
    date_debut: '',
    date_fin: '',
    lieu: '',
    budget: '',
    statut: 'planifie'
  }
  modalEvenementOuvert.value = true
}

const fermerModalEvenement = () => {
  modalEvenementOuvert.value = false
  evenementEnEdition.value = null
}

const sauvegarderEvenement = () => {
  if (evenementEnEdition.value) {
    // Modification
    const index = evenements.value.findIndex(e => e.id === evenementEnEdition.value.id)
    if (index !== -1) {
      evenements.value[index] = { ...evenements.value[index], ...formEvenement.value }
    }
  } else {
    // Ajout
    const nouvelEvenement = {
      id: Date.now(),
      ...formEvenement.value,
      type_francais: getTypeFrancais(formEvenement.value.type),
      statut_francais: getStatutFrancais(formEvenement.value.statut),
      membres_selectionnes: {},
      configuration: {},
      nombre_total_membres: 0
    }
    evenements.value.push(nouvelEvenement)
  }
  
  fermerModalEvenement()
  window.addToast('Événement sauvegardé avec succès !', 'success')
}

const voirDetailsEvenement = (evenement) => {
  evenementSelectionne.value = evenement
  modalDetailsOuvert.value = true
}

const fermerModalDetails = () => {
  modalDetailsOuvert.value = false
  evenementSelectionne.value = null
}

const editerEvenement = (evenement) => {
  evenementEnEdition.value = evenement
  formEvenement.value = { ...evenement }
  modalEvenementOuvert.value = true
}

const gererParticipants = (evenement) => {
  evenementSelectionne.value = evenement
  prestationActive.value = 'declamation'
  modalParticipantsOuvert.value = true
}

const fermerModalParticipants = () => {
  modalParticipantsOuvert.value = false
  evenementSelectionne.value = null
}

const toggleParticipant = (membreId) => {
  if (!evenementSelectionne.value) return
  
  const prestation = prestationActive.value
  if (!evenementSelectionne.value.membres_selectionnes) {
    evenementSelectionne.value.membres_selectionnes = {}
  }
  
  if (!evenementSelectionne.value.membres_selectionnes[prestation]) {
    evenementSelectionne.value.membres_selectionnes[prestation] = []
  }
  
  const participants = evenementSelectionne.value.membres_selectionnes[prestation]
  const index = participants.indexOf(membreId)
  
  if (index > -1) {
    participants.splice(index, 1)
  } else {
    participants.push(membreId)
  }
  
  // Recalculer le nombre total de membres
  evenementSelectionne.value.nombre_total_membres = Object.values(evenementSelectionne.value.membres_selectionnes)
    .flat()
    .filter((id, index, arr) => arr.indexOf(id) === index).length
}

const ajouterParticipant = () => {
  window.addToast('Fonctionnalité d\'ajout de participant', 'info')
}

const sauvegarderParticipants = () => {
  fermerModalParticipants()
  window.addToast('Participants sauvegardés avec succès !', 'success')
}

const reinitialiserFiltres = () => {
  filtres.value = {
    type: '',
    statut: '',
    periode: ''
  }
}

const pagePrecedente = () => {
  if (pageCourante.value > 1) {
    pageCourante.value--
  }
}

const pageSuivante = () => {
  if (pageCourante.value < totalPages.value) {
    pageCourante.value++
  }
}

const getTypeFrancais = (type) => {
  const types = {
    'magal': 'Magal',
    'gamou': 'Gamou',
    'promokhane': 'Promokhane',
    'conference': 'Conférence',
    'formation': 'Formation',
    'autre': 'Autre'
  }
  return types[type] || 'Autre'
}

const getStatutFrancais = (statut) => {
  const statuts = {
    'planifie': 'Planifié',
    'confirme': 'Confirmé',
    'en_cours': 'En cours',
    'termine': 'Terminé',
    'annule': 'Annulé'
  }
  return statuts[statut] || 'Planifié'
}

// Initialisation
onMounted(() => {
  console.log('Composant Événements initialisé')
})
</script>
