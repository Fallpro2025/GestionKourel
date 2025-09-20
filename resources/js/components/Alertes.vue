<template>
  <div class="space-y-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Système d'Alertes</h1>
        <p class="text-gray-600 mt-2">Surveillez et gérez les alertes automatiques</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern" @click="ouvrirModalRegle">
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouvelle règle
        </button>
      </div>
    </div>

    <!-- Statistiques des alertes -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Alertes Actives</p>
            <p class="kpi-value">{{ statistiques.alertesActives }}</p>
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

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Critiques</p>
            <p class="kpi-value">{{ statistiques.critiques }}</p>
            <div class="kpi-change negative">
              <ArrowDownIcon class="w-4 h-4" />
              -1 cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <XCircleIcon class="w-6 h-6 text-purple-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Résolues</p>
            <p class="kpi-value">{{ statistiques.resolues }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +8 cette semaine
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
            <p class="text-sm font-medium text-gray-600">Règles Actives</p>
            <p class="kpi-value">{{ statistiques.reglesActives }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +2 ce mois
            </div>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <CogIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card-modern p-6">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Type d'alerte</label>
          <select 
            v-model="filtres.type"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les types</option>
            <option value="absence_repetitive">Absence répétitive</option>
            <option value="absence_non_justifiee">Absence non justifiée</option>
            <option value="retard_excessif">Retard excessif</option>
            <option value="cotisation_retard">Cotisation en retard</option>
            <option value="evenement_majeur">Événement majeur</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'urgence</label>
          <select 
            v-model="filtres.niveau"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les niveaux</option>
            <option value="info">Information</option>
            <option value="warning">Attention</option>
            <option value="error">Erreur</option>
            <option value="critical">Critique</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
          <select 
            v-model="filtres.statut"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les statuts</option>
            <option value="nouveau">Nouveau</option>
            <option value="envoye">Envoyé</option>
            <option value="lu">Lu</option>
            <option value="resolu">Résolu</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Membre</label>
          <select 
            v-model="filtres.membre"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les membres</option>
            <option v-for="membre in membres" :key="membre.id" :value="membre.id">
              {{ membre.nom_complet }}
            </option>
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

    <!-- Onglets -->
    <div class="card-modern">
      <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6">
          <button 
            v-for="onglet in onglets" 
            :key="onglet.id"
            @click="ongletActif = onglet.id"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              ongletActif === onglet.id 
                ? 'border-blue-500 text-blue-600' 
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            {{ onglet.label }}
          </button>
        </nav>
      </div>

      <!-- Contenu des onglets -->
      <div class="p-6">
        <!-- Onglet Alertes -->
        <div v-if="ongletActif === 'alertes'">
          <div class="space-y-4">
            <div 
              v-for="alerte in alertesFiltrees" 
              :key="alerte.id"
              :class="[
                'p-4 border rounded-lg transition-colors cursor-pointer hover:shadow-md',
                getAlerteBorderClass(alerte.niveau_urgence)
              ]"
              @click="voirDetailsAlerte(alerte)"
            >
              <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                  <div :class="getAlerteIconClass(alerte.niveau_urgence)" class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                    <component :is="getAlerteIcon(alerte.niveau_urgence)" class="w-4 h-4" />
                  </div>
                  <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">{{ alerte.titre }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ alerte.message }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                      <span>{{ alerte.type_francais }}</span>
                      <span>{{ alerte.niveau_urgence_francais }}</span>
                      <span>{{ formatDate(alerte.created_at) }}</span>
                      <span v-if="alerte.membre">{{ alerte.membre.nom_complet }}</span>
                    </div>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span :class="getStatutAlerteBadgeClass(alerte.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ alerte.statut_francais }}
                  </span>
                  <div class="flex space-x-1">
                    <button 
                      @click.stop="marquerCommeLue(alerte)"
                      v-if="alerte.statut === 'envoye'"
                      class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                      title="Marquer comme lu"
                    >
                      <EyeIcon class="w-4 h-4" />
                    </button>
                    <button 
                      @click.stop="resoudreAlerte(alerte)"
                      v-if="alerte.statut !== 'resolu'"
                      class="p-1 text-gray-400 hover:text-green-600 transition-colors"
                      title="Résoudre"
                    >
                      <CheckIcon class="w-4 h-4" />
                    </button>
                    <button 
                      @click.stop="supprimerAlerte(alerte)"
                      class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                      title="Supprimer"
                    >
                      <TrashIcon class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Onglet Règles -->
        <div v-if="ongletActif === 'regles'">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Règles d'Alerte Automatiques</h3>
            <button class="btn-modern" @click="ouvrirModalRegle">
              <PlusIcon class="w-5 h-5 mr-2" />
              Nouvelle règle
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div 
              v-for="regle in regles" 
              :key="regle.id"
              class="card-modern p-6 hover-lift"
            >
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h4 class="font-semibold text-gray-900">{{ regle.nom }}</h4>
                  <p class="text-sm text-gray-600">{{ regle.description }}</p>
                </div>
                <div class="flex items-center space-x-2">
                  <span :class="regle.actif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ regle.actif ? 'Actif' : 'Inactif' }}
                  </span>
                  <button 
                    @click="editerRegle(regle)"
                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                    title="Éditer"
                  >
                    <PencilIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-medium">{{ regle.type_francais }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Condition:</span>
                  <span class="font-medium">{{ regle.condition }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Niveau:</span>
                  <span :class="getNiveauUrgenceClass(regle.niveau_urgence)" class="px-2 py-1 rounded-full text-xs font-medium">
                    {{ regle.niveau_urgence_francais }}
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Notifications:</span>
                  <span class="font-medium">{{ regle.canaux.join(', ') }}</span>
                </div>
              </div>

              <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                  {{ regle.alertes_generees }} alertes générées
                </div>
                <div class="flex space-x-2">
                  <button 
                    @click="toggleRegle(regle)"
                    :class="regle.actif ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                    class="text-sm font-medium transition-colors"
                  >
                    {{ regle.actif ? 'Désactiver' : 'Activer' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de détails d'alerte -->
    <div v-if="modalDetailsOuvert" class="modal-overlay" @click="fermerModalDetails">
      <div class="modal-content max-w-2xl" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">{{ alerteSelectionnee?.titre }}</h3>
          <button 
            @click="fermerModalDetails"
            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
          >
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <div v-if="alerteSelectionnee" class="space-y-6">
          <!-- Informations générales -->
          <div class="card-modern p-4">
            <h4 class="font-semibold text-gray-900 mb-3">Informations</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Type:</span>
                <span class="font-medium">{{ alerteSelectionnee.type_francais }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Niveau:</span>
                <span :class="getNiveauUrgenceClass(alerteSelectionnee.niveau_urgence)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ alerteSelectionnee.niveau_urgence_francais }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Statut:</span>
                <span :class="getStatutAlerteBadgeClass(alerteSelectionnee.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ alerteSelectionnee.statut_francais }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Créée le:</span>
                <span class="font-medium">{{ formatDateTime(alerteSelectionnee.created_at) }}</span>
              </div>
              <div v-if="alerteSelectionnee.sent_at" class="flex justify-between">
                <span class="text-gray-600">Envoyée le:</span>
                <span class="font-medium">{{ formatDateTime(alerteSelectionnee.sent_at) }}</span>
              </div>
              <div v-if="alerteSelectionnee.resolved_at" class="flex justify-between">
                <span class="text-gray-600">Résolue le:</span>
                <span class="font-medium">{{ formatDateTime(alerteSelectionnee.resolved_at) }}</span>
              </div>
            </div>
          </div>

          <!-- Message -->
          <div class="card-modern p-4">
            <h4 class="font-semibold text-gray-900 mb-3">Message</h4>
            <p class="text-sm text-gray-600">{{ alerteSelectionnee.message }}</p>
          </div>

          <!-- Contexte -->
          <div v-if="alerteSelectionnee.membre || alerteSelectionnee.activite || alerteSelectionnee.evenement" class="card-modern p-4">
            <h4 class="font-semibold text-gray-900 mb-3">Contexte</h4>
            <div class="space-y-2 text-sm">
              <div v-if="alerteSelectionnee.membre" class="flex justify-between">
                <span class="text-gray-600">Membre:</span>
                <span class="font-medium">{{ alerteSelectionnee.membre.nom_complet }}</span>
              </div>
              <div v-if="alerteSelectionnee.activite" class="flex justify-between">
                <span class="text-gray-600">Activité:</span>
                <span class="font-medium">{{ alerteSelectionnee.activite.nom }}</span>
              </div>
              <div v-if="alerteSelectionnee.evenement" class="flex justify-between">
                <span class="text-gray-600">Événement:</span>
                <span class="font-medium">{{ alerteSelectionnee.evenement.nom }}</span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end space-x-4">
            <button 
              @click="fermerModalDetails"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Fermer
            </button>
            <button 
              @click="resoudreAlerte(alerteSelectionnee)"
              v-if="alerteSelectionnee.statut !== 'resolu'"
              class="btn-modern"
            >
              Résoudre l'alerte
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de création/édition de règle -->
    <div v-if="modalRegleOuvert" class="modal-overlay" @click="fermerModalRegle">
      <div class="modal-content max-w-2xl" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          {{ regleEnEdition ? 'Modifier la règle' : 'Nouvelle règle d\'alerte' }}
        </h3>
        
        <form @submit.prevent="sauvegarderRegle" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type d'alerte *</label>
              <select 
                v-model="formRegle.type"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="absence_repetitive">Absence répétitive</option>
                <option value="absence_non_justifiee">Absence non justifiée</option>
                <option value="retard_excessif">Retard excessif</option>
                <option value="cotisation_retard">Cotisation en retard</option>
                <option value="evenement_majeur">Événement majeur</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'urgence *</label>
              <select 
                v-model="formRegle.niveau_urgence"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="info">Information</option>
                <option value="warning">Attention</option>
                <option value="error">Erreur</option>
                <option value="critical">Critique</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la règle *</label>
            <input 
              v-model="formRegle.nom"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formRegle.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
            <input 
              v-model="formRegle.condition"
              type="text" 
              required
              placeholder="Ex: 3 absences consécutives"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Canaux de notification</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <label class="flex items-center space-x-2">
                <input 
                  v-model="formRegle.canaux"
                  type="checkbox" 
                  value="email"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="text-sm">Email</span>
              </label>
              <label class="flex items-center space-x-2">
                <input 
                  v-model="formRegle.canaux"
                  type="checkbox" 
                  value="sms"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="text-sm">SMS</span>
              </label>
              <label class="flex items-center space-x-2">
                <input 
                  v-model="formRegle.canaux"
                  type="checkbox" 
                  value="whatsapp"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="text-sm">WhatsApp</span>
              </label>
              <label class="flex items-center space-x-2">
                <input 
                  v-model="formRegle.canaux"
                  type="checkbox" 
                  value="push"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="text-sm">Push</span>
              </label>
            </div>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModalRegle"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              {{ regleEnEdition ? 'Modifier' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  PlusIcon,
  ExclamationTriangleIcon,
  XCircleIcon,
  CheckCircleIcon,
  CogIcon,
  ArrowDownIcon,
  ArrowUpIcon,
  EyeIcon,
  CheckIcon,
  TrashIcon,
  PencilIcon,
  XMarkIcon,
  BellIcon
} from '@heroicons/vue/24/outline'

// État réactif
const ongletActif = ref('alertes')
const modalDetailsOuvert = ref(false)
const modalRegleOuvert = ref(false)
const alerteSelectionnee = ref(null)
const regleEnEdition = ref(null)

const filtres = ref({
  type: '',
  niveau: '',
  statut: '',
  membre: ''
})

const statistiques = ref({
  alertesActives: 7,
  critiques: 2,
  resolues: 45,
  reglesActives: 8
})

const formRegle = ref({
  type: 'absence_repetitive',
  nom: '',
  description: '',
  condition: '',
  niveau_urgence: 'warning',
  canaux: ['email']
})

// Données simulées
const membres = ref([
  { id: 1, nom_complet: 'Moussa Fall' },
  { id: 2, nom_complet: 'Aminata Diop' },
  { id: 3, nom_complet: 'Ibrahima Ba' }
])

const alertes = ref([
  {
    id: 1,
    type: 'absence_repetitive',
    type_francais: 'Absence répétitive',
    titre: 'Absences répétitives - Moussa Fall',
    message: 'Moussa Fall a 3 absences consécutives aux répétitions',
    niveau_urgence: 'warning',
    niveau_urgence_francais: 'Attention',
    statut: 'nouveau',
    statut_francais: 'Nouveau',
    membre: { nom_complet: 'Moussa Fall' },
    activite: { nom: 'Répétition Chorale' },
    created_at: '2025-01-15T10:30:00',
    sent_at: null,
    resolved_at: null
  },
  {
    id: 2,
    type: 'cotisation_retard',
    type_francais: 'Cotisation en retard',
    titre: 'Cotisation en retard - Aminata Diop',
    message: 'Aminata Diop a une cotisation en retard de 15 jours',
    niveau_urgence: 'error',
    niveau_urgence_francais: 'Erreur',
    statut: 'envoye',
    statut_francais: 'Envoyé',
    membre: { nom_complet: 'Aminata Diop' },
    created_at: '2025-01-15T09:15:00',
    sent_at: '2025-01-15T09:20:00',
    resolved_at: null
  },
  {
    id: 3,
    type: 'evenement_majeur',
    type_francais: 'Événement majeur',
    titre: 'Événement majeur - Magal de Touba',
    message: 'Le Magal de Touba approche, vérifiez la préparation',
    niveau_urgence: 'info',
    niveau_urgence_francais: 'Information',
    statut: 'lu',
    statut_francais: 'Lu',
    evenement: { nom: 'Magal de Touba 2025' },
    created_at: '2025-01-14T16:45:00',
    sent_at: '2025-01-14T16:50:00',
    resolved_at: null
  }
])

const regles = ref([
  {
    id: 1,
    type: 'absence_repetitive',
    type_francais: 'Absence répétitive',
    nom: 'Absences répétitives',
    description: 'Alerte en cas d\'absences répétitives',
    condition: '3 absences consécutives',
    niveau_urgence: 'warning',
    niveau_urgence_francais: 'Attention',
    canaux: ['email', 'sms'],
    actif: true,
    alertes_generees: 12
  },
  {
    id: 2,
    type: 'cotisation_retard',
    type_francais: 'Cotisation en retard',
    nom: 'Cotisations en retard',
    description: 'Alerte pour les cotisations en retard',
    condition: 'Retard de plus de 7 jours',
    niveau_urgence: 'error',
    niveau_urgence_francais: 'Erreur',
    canaux: ['email', 'whatsapp'],
    actif: true,
    alertes_generees: 8
  },
  {
    id: 3,
    type: 'retard_excessif',
    type_francais: 'Retard excessif',
    nom: 'Retards excessifs',
    description: 'Alerte pour les retards répétés',
    condition: '3 retards de plus de 10 minutes',
    niveau_urgence: 'warning',
    niveau_urgence_francais: 'Attention',
    canaux: ['email'],
    actif: false,
    alertes_generees: 5
  }
])

// Configuration des onglets
const onglets = [
  { id: 'alertes', label: 'Alertes' },
  { id: 'regles', label: 'Règles' }
]

// Computed properties
const alertesFiltrees = computed(() => {
  let resultat = alertes.value

  // Filtre par type
  if (filtres.value.type) {
    resultat = resultat.filter(alerte => alerte.type === filtres.value.type)
  }

  // Filtre par niveau
  if (filtres.value.niveau) {
    resultat = resultat.filter(alerte => alerte.niveau_urgence === filtres.value.niveau)
  }

  // Filtre par statut
  if (filtres.value.statut) {
    resultat = resultat.filter(alerte => alerte.statut === filtres.value.statut)
  }

  // Filtre par membre
  if (filtres.value.membre) {
    resultat = resultat.filter(alerte => alerte.membre?.id == filtres.value.membre)
  }

  return resultat
})

// Fonctions utilitaires
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR')
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

const getAlerteIcon = (niveau) => {
  const icons = {
    'critical': XCircleIcon,
    'error': XCircleIcon,
    'warning': ExclamationTriangleIcon,
    'info': BellIcon
  }
  return icons[niveau] || BellIcon
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

const getAlerteBorderClass = (niveau) => {
  const classes = {
    'critical': 'border-red-200 bg-red-50',
    'error': 'border-red-200 bg-red-50',
    'warning': 'border-yellow-200 bg-yellow-50',
    'info': 'border-blue-200 bg-blue-50'
  }
  return classes[niveau] || 'border-gray-200 bg-gray-50'
}

const getStatutAlerteBadgeClass = (statut) => {
  const classes = {
    'nouveau': 'bg-blue-100 text-blue-800',
    'envoye': 'bg-yellow-100 text-yellow-800',
    'lu': 'bg-green-100 text-green-800',
    'resolu': 'bg-gray-100 text-gray-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getNiveauUrgenceClass = (niveau) => {
  const classes = {
    'critical': 'bg-red-100 text-red-800',
    'error': 'bg-red-100 text-red-800',
    'warning': 'bg-yellow-100 text-yellow-800',
    'info': 'bg-blue-100 text-blue-800'
  }
  return classes[niveau] || 'bg-gray-100 text-gray-800'
}

// Fonctions de gestion des alertes
const voirDetailsAlerte = (alerte) => {
  alerteSelectionnee.value = alerte
  modalDetailsOuvert.value = true
}

const fermerModalDetails = () => {
  modalDetailsOuvert.value = false
  alerteSelectionnee.value = null
}

const marquerCommeLue = (alerte) => {
  alerte.statut = 'lu'
  alerte.statut_francais = 'Lu'
  window.addToast('Alerte marquée comme lue', 'success')
}

const resoudreAlerte = (alerte) => {
  alerte.statut = 'resolu'
  alerte.statut_francais = 'Résolu'
  alerte.resolved_at = new Date().toISOString()
  fermerModalDetails()
  window.addToast('Alerte résolue avec succès', 'success')
}

const supprimerAlerte = (alerte) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')) {
    const index = alertes.value.findIndex(a => a.id === alerte.id)
    if (index !== -1) {
      alertes.value.splice(index, 1)
      window.addToast('Alerte supprimée avec succès', 'success')
    }
  }
}

// Fonctions de gestion des règles
const ouvrirModalRegle = () => {
  regleEnEdition.value = null
  formRegle.value = {
    type: 'absence_repetitive',
    nom: '',
    description: '',
    condition: '',
    niveau_urgence: 'warning',
    canaux: ['email']
  }
  modalRegleOuvert.value = true
}

const fermerModalRegle = () => {
  modalRegleOuvert.value = false
  regleEnEdition.value = null
}

const sauvegarderRegle = () => {
  if (regleEnEdition.value) {
    // Modification
    const index = regles.value.findIndex(r => r.id === regleEnEdition.value.id)
    if (index !== -1) {
      regles.value[index] = { ...regles.value[index], ...formRegle.value }
    }
  } else {
    // Ajout
    const nouvelleRegle = {
      id: Date.now(),
      ...formRegle.value,
      type_francais: getTypeFrancais(formRegle.value.type),
      niveau_urgence_francais: getNiveauUrgenceFrancais(formRegle.value.niveau_urgence),
      actif: true,
      alertes_generees: 0
    }
    regles.value.push(nouvelleRegle)
  }
  
  fermerModalRegle()
  window.addToast('Règle sauvegardée avec succès !', 'success')
}

const editerRegle = (regle) => {
  regleEnEdition.value = regle
  formRegle.value = { ...regle }
  modalRegleOuvert.value = true
}

const toggleRegle = (regle) => {
  regle.actif = !regle.actif
  window.addToast(`Règle ${regle.actif ? 'activée' : 'désactivée'}`, 'success')
}

const reinitialiserFiltres = () => {
  filtres.value = {
    type: '',
    niveau: '',
    statut: '',
    membre: ''
  }
}

const getTypeFrancais = (type) => {
  const types = {
    'absence_repetitive': 'Absence répétitive',
    'absence_non_justifiee': 'Absence non justifiée',
    'retard_excessif': 'Retard excessif',
    'cotisation_retard': 'Cotisation en retard',
    'evenement_majeur': 'Événement majeur'
  }
  return types[type] || 'Autre'
}

const getNiveauUrgenceFrancais = (niveau) => {
  const niveaux = {
    'info': 'Information',
    'warning': 'Attention',
    'error': 'Erreur',
    'critical': 'Critique'
  }
  return niveaux[niveau] || 'Information'
}

// Initialisation
onMounted(() => {
  console.log('Composant Alertes initialisé')
})
</script>
