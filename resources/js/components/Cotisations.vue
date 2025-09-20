<template>
  <div class="space-y-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Cotisations</h1>
        <p class="text-gray-600 mt-2">Suivez les projets de cotisation et les paiements</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern" @click="ouvrirModalProjet">
          <PlusIcon class="w-5 h-5 mr-2" />
          Nouveau projet
        </button>
      </div>
    </div>

    <!-- Statistiques des cotisations -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Total Collecté</p>
            <p class="kpi-value">{{ formatCurrency(statistiques.totalCollecte) }}</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +15% ce mois
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
            <p class="text-sm font-medium text-gray-600">Taux de Recouvrement</p>
            <p class="kpi-value">{{ statistiques.tauxRecouvrement }}%</p>
            <div class="kpi-change positive">
              <ArrowUpIcon class="w-4 h-4" />
              +3% cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <ChartBarIcon class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="kpi-card hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">En Retard</p>
            <p class="kpi-value">{{ statistiques.enRetard }}</p>
            <div class="kpi-change negative">
              <ArrowDownIcon class="w-4 h-4" />
              -2 cette semaine
            </div>
          </div>
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <ExclamationTriangleIcon class="w-6 h-6 text-red-600" />
          </div>
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
        <!-- Onglet Projets -->
        <div v-if="ongletActif === 'projets'">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Projets de Cotisation</h3>
            <button class="btn-modern" @click="ouvrirModalProjet">
              <PlusIcon class="w-5 h-5 mr-2" />
              Nouveau projet
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div v-for="projet in projets" :key="projet.id" class="card-modern p-6 hover-lift">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h4 class="text-lg font-semibold text-gray-900">{{ projet.nom }}</h4>
                  <p class="text-sm text-gray-600">{{ projet.description }}</p>
                </div>
                <span :class="getStatutProjetBadgeClass(projet.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ projet.statut }}
                </span>
              </div>

              <div class="space-y-3">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Montant total:</span>
                  <span class="font-medium">{{ formatCurrency(projet.montant_total) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Collecté:</span>
                  <span class="font-medium">{{ formatCurrency(projet.montant_collecte) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Restant:</span>
                  <span class="font-medium">{{ formatCurrency(projet.montant_restant) }}</span>
                </div>
              </div>

              <!-- Barre de progression -->
              <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-600">Progression</span>
                  <span class="font-medium">{{ projet.pourcentage_collecte }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-300"
                    :style="{ width: `${projet.pourcentage_collecte}%` }"
                  ></div>
                </div>
              </div>

              <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                  {{ projet.date_debut }} - {{ projet.date_fin }}
                </div>
                <div class="flex space-x-2">
                  <button 
                    @click="voirDetailsProjet(projet)"
                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                    title="Voir les détails"
                  >
                    <EyeIcon class="w-4 h-4" />
                  </button>
                  <button 
                    @click="editerProjet(projet)"
                    class="p-2 text-gray-400 hover:text-green-600 transition-colors"
                    title="Éditer"
                  >
                    <PencilIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Onglet Assignations -->
        <div v-if="ongletActif === 'assignations'">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Assignations par Membre</h3>
            <div class="flex items-center space-x-4">
              <select 
                v-model="filtreProjet"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Tous les projets</option>
                <option v-for="projet in projets" :key="projet.id" :value="projet.id">
                  {{ projet.nom }}
                </option>
              </select>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="table-modern w-full">
              <thead>
                <tr>
                  <th class="text-left">Membre</th>
                  <th class="text-left">Projet</th>
                  <th class="text-left">Montant Assigné</th>
                  <th class="text-left">Montant Payé</th>
                  <th class="text-left">Statut</th>
                  <th class="text-left">Échéance</th>
                  <th class="text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="assignation in assignationsFiltrees" :key="assignation.id" class="hover:bg-gray-50">
                  <td class="py-4">
                    <div class="flex items-center space-x-3">
                      <img 
                        :src="assignation.membre.photo_url || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
                        :alt="assignation.membre.nom_complet"
                        class="w-10 h-10 rounded-full"
                      >
                      <div>
                        <p class="font-medium text-gray-900">{{ assignation.membre.nom_complet }}</p>
                        <p class="text-sm text-gray-600">{{ assignation.membre.email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4">
                    <span class="text-sm text-gray-900">{{ assignation.projet.nom }}</span>
                  </td>
                  <td class="py-4">
                    <span class="font-medium text-gray-900">{{ formatCurrency(assignation.montant_assigné) }}</span>
                  </td>
                  <td class="py-4">
                    <span class="font-medium text-gray-900">{{ formatCurrency(assignation.montant_payé) }}</span>
                  </td>
                  <td class="py-4">
                    <span :class="getStatutPaiementBadgeClass(assignation.statut_paiement)" class="px-2 py-1 rounded-full text-xs font-medium">
                      {{ assignation.statut_paiement }}
                    </span>
                  </td>
                  <td class="py-4">
                    <span :class="assignation.en_retard ? 'text-red-600 font-medium' : 'text-gray-600'">
                      {{ formatDate(assignation.date_echeance) }}
                    </span>
                  </td>
                  <td class="py-4">
                    <div class="flex items-center justify-end space-x-2">
                      <button 
                        @click="enregistrerPaiement(assignation)"
                        class="p-2 text-gray-400 hover:text-green-600 transition-colors"
                        title="Enregistrer un paiement"
                      >
                        <CurrencyDollarIcon class="w-4 h-4" />
                      </button>
                      <button 
                        @click="voirHistorique(assignation)"
                        class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                        title="Voir l'historique"
                      >
                        <ClockIcon class="w-4 h-4" />
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

    <!-- Modal de création/édition de projet -->
    <div v-if="modalProjetOuvert" class="modal-overlay" @click="fermerModalProjet">
      <div class="modal-content" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          {{ projetEnEdition ? 'Modifier le projet' : 'Nouveau projet de cotisation' }}
        </h3>
        
        <form @submit.prevent="sauvegarderProjet" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom du projet *</label>
            <input 
              v-model="formProjet.nom"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formProjet.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Montant total *</label>
              <input 
                v-model="formProjet.montant_total"
                type="number" 
                required
                min="0"
                step="100"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type de cotisation</label>
              <select 
                v-model="formProjet.type_cotisation"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="obligatoire">Obligatoire</option>
                <option value="volontaire">Volontaire</option>
                <option value="evenement">Événement</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
              <input 
                v-model="formProjet.date_debut"
                type="date" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
              <input 
                v-model="formProjet.date_fin"
                type="date" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModalProjet"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              {{ projetEnEdition ? 'Modifier' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal d'enregistrement de paiement -->
    <div v-if="modalPaiementOuvert" class="modal-overlay" @click="fermerModalPaiement">
      <div class="modal-content" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          Enregistrer un paiement
        </h3>
        
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
          <p class="text-sm text-gray-600">Membre: <span class="font-medium">{{ assignationSelectionnee?.membre?.nom_complet }}</span></p>
          <p class="text-sm text-gray-600">Projet: <span class="font-medium">{{ assignationSelectionnee?.projet?.nom }}</span></p>
          <p class="text-sm text-gray-600">Montant restant: <span class="font-medium text-green-600">{{ formatCurrency(assignationSelectionnee?.montant_restant || 0) }}</span></p>
        </div>
        
        <form @submit.prevent="confirmerPaiement" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Montant payé *</label>
            <input 
              v-model="formPaiement.montant"
              type="number" 
              required
              :max="assignationSelectionnee?.montant_restant || 0"
              min="0"
              step="100"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
            <select 
              v-model="formPaiement.methode"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="especes">Espèces</option>
              <option value="virement">Virement bancaire</option>
              <option value="mobile_money">Mobile Money</option>
              <option value="cheque">Chèque</option>
            </select>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModalPaiement"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              Enregistrer le paiement
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
  CurrencyDollarIcon,
  ChartBarIcon,
  ExclamationTriangleIcon,
  ArrowUpIcon,
  ArrowDownIcon,
  EyeIcon,
  PencilIcon,
  ClockIcon
} from '@heroicons/vue/24/outline'

// État réactif
const ongletActif = ref('projets')
const modalProjetOuvert = ref(false)
const modalPaiementOuvert = ref(false)
const projetEnEdition = ref(null)
const assignationSelectionnee = ref(null)
const filtreProjet = ref('')

const statistiques = ref({
  totalCollecte: 12500000,
  tauxRecouvrement: 78.5,
  enRetard: 12
})

const formProjet = ref({
  nom: '',
  description: '',
  montant_total: '',
  type_cotisation: 'obligatoire',
  date_debut: '',
  date_fin: ''
})

const formPaiement = ref({
  montant: '',
  methode: 'especes'
})

// Données simulées
const projets = ref([
  {
    id: 1,
    nom: 'Cotisation Annuelle 2025',
    description: 'Cotisation obligatoire pour l\'année 2025',
    montant_total: 5000000,
    montant_collecte: 3750000,
    montant_restant: 1250000,
    pourcentage_collecte: 75,
    date_debut: '2025-01-01',
    date_fin: '2025-12-31',
    statut: 'actif',
    type_cotisation: 'obligatoire'
  },
  {
    id: 2,
    nom: 'Projet Mosquée',
    description: 'Construction d\'une nouvelle mosquée',
    montant_total: 10000000,
    montant_collecte: 6500000,
    montant_restant: 3500000,
    pourcentage_collecte: 65,
    date_debut: '2025-01-15',
    date_fin: '2025-06-30',
    statut: 'actif',
    type_cotisation: 'volontaire'
  }
])

const assignations = ref([
  {
    id: 1,
    membre: {
      id: 1,
      nom_complet: 'Moussa Fall',
      email: 'moussa.fall@email.com',
      photo_url: null
    },
    projet: {
      id: 1,
      nom: 'Cotisation Annuelle 2025'
    },
    montant_assigné: 100000,
    montant_payé: 100000,
    montant_restant: 0,
    statut_paiement: 'paye',
    date_echeance: '2025-03-31',
    en_retard: false
  },
  {
    id: 2,
    membre: {
      id: 2,
      nom_complet: 'Aminata Diop',
      email: 'aminata.diop@email.com',
      photo_url: null
    },
    projet: {
      id: 1,
      nom: 'Cotisation Annuelle 2025'
    },
    montant_assigné: 100000,
    montant_payé: 50000,
    montant_restant: 50000,
    statut_paiement: 'partiel',
    date_echeance: '2025-03-31',
    en_retard: false
  },
  {
    id: 3,
    membre: {
      id: 3,
      nom_complet: 'Ibrahima Ba',
      email: 'ibrahima.ba@email.com',
      photo_url: null
    },
    projet: {
      id: 1,
      nom: 'Cotisation Annuelle 2025'
    },
    montant_assigné: 100000,
    montant_payé: 0,
    montant_restant: 100000,
    statut_paiement: 'non_paye',
    date_echeance: '2025-02-15',
    en_retard: true
  }
])

// Computed properties
const assignationsFiltrees = computed(() => {
  if (!filtreProjet.value) {
    return assignations.value
  }
  return assignations.value.filter(assignation => assignation.projet.id == filtreProjet.value)
})

// Configuration des onglets
const onglets = [
  { id: 'projets', label: 'Projets' },
  { id: 'assignations', label: 'Assignations' }
]

// Fonctions utilitaires
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(amount)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR')
}

const getStatutProjetBadgeClass = (statut) => {
  const classes = {
    'planifie': 'bg-gray-100 text-gray-800',
    'actif': 'bg-green-100 text-green-800',
    'suspendu': 'bg-yellow-100 text-yellow-800',
    'termine': 'bg-blue-100 text-blue-800',
    'annule': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getStatutPaiementBadgeClass = (statut) => {
  const classes = {
    'non_paye': 'bg-red-100 text-red-800',
    'partiel': 'bg-yellow-100 text-yellow-800',
    'paye': 'bg-green-100 text-green-800',
    'rembourse': 'bg-gray-100 text-gray-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

// Fonctions de gestion des projets
const ouvrirModalProjet = () => {
  projetEnEdition.value = null
  formProjet.value = {
    nom: '',
    description: '',
    montant_total: '',
    type_cotisation: 'obligatoire',
    date_debut: '',
    date_fin: ''
  }
  modalProjetOuvert.value = true
}

const editerProjet = (projet) => {
  projetEnEdition.value = projet
  formProjet.value = { ...projet }
  modalProjetOuvert.value = true
}

const fermerModalProjet = () => {
  modalProjetOuvert.value = false
  projetEnEdition.value = null
}

const sauvegarderProjet = () => {
  if (projetEnEdition.value) {
    // Modification
    const index = projets.value.findIndex(p => p.id === projetEnEdition.value.id)
    if (index !== -1) {
      projets.value[index] = { ...projets.value[index], ...formProjet.value }
    }
  } else {
    // Ajout
    const nouveauProjet = {
      id: Date.now(),
      ...formProjet.value,
      montant_collecte: 0,
      montant_restant: parseFloat(formProjet.value.montant_total),
      pourcentage_collecte: 0,
      statut: 'planifie'
    }
    projets.value.push(nouveauProjet)
  }
  
  fermerModalProjet()
  window.addToast('Projet sauvegardé avec succès !', 'success')
}

const voirDetailsProjet = (projet) => {
  window.addToast(`Détails du projet: ${projet.nom}`, 'info')
}

// Fonctions de gestion des paiements
const enregistrerPaiement = (assignation) => {
  assignationSelectionnee.value = assignation
  formPaiement.value = {
    montant: '',
    methode: 'especes'
  }
  modalPaiementOuvert.value = true
}

const fermerModalPaiement = () => {
  modalPaiementOuvert.value = false
  assignationSelectionnee.value = null
}

const confirmerPaiement = () => {
  if (assignationSelectionnee.value) {
    const montantPaye = parseFloat(formPaiement.value.montant)
    const assignation = assignationSelectionnee.value
    
    // Mettre à jour l'assignation
    assignation.montant_payé += montantPaye
    assignation.montant_restant = assignation.montant_assigné - assignation.montant_payé
    
    // Déterminer le nouveau statut
    if (assignation.montant_restant <= 0) {
      assignation.statut_paiement = 'paye'
    } else {
      assignation.statut_paiement = 'partiel'
    }
    
    fermerModalPaiement()
    window.addToast('Paiement enregistré avec succès !', 'success')
  }
}

const voirHistorique = (assignation) => {
  window.addToast(`Historique des paiements pour ${assignation.membre.nom_complet}`, 'info')
}

// Initialisation
onMounted(() => {
  console.log('Composant Cotisations initialisé')
})
</script>