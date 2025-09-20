<template>
  <div id="app" class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
    <!-- Navigation moderne -->
    <nav class="nav-modern">
      <div class="container-modern">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-green-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">DK</span>
              </div>
              <h1 class="text-xl font-bold text-gray-800">Gestion Kourel</h1>
            </div>
          </div>

          <!-- Navigation principale -->
          <div class="hidden md:flex items-center space-x-1">
            <router-link 
              v-for="item in menuItems" 
              :key="item.name"
              :to="item.path" 
              class="nav-item"
              :class="{ 'active': $route.name === item.name }"
            >
              <component :is="item.icon" class="w-5 h-5 mr-2" />
              {{ item.label }}
            </router-link>
          </div>

          <!-- Menu utilisateur -->
          <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
              <BellIcon class="w-6 h-6" />
              <span v-if="notificationsCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ notificationsCount }}
              </span>
            </button>

            <!-- Profil utilisateur -->
            <div class="flex items-center space-x-2">
              <img 
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" 
                alt="Profil" 
                class="w-8 h-8 rounded-full"
              >
              <span class="text-sm font-medium text-gray-700">Admin</span>
            </div>
          </div>

          <!-- Menu mobile -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600">
            <Bars3Icon class="w-6 h-6" />
          </button>
        </div>

        <!-- Menu mobile -->
        <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200">
          <div class="flex flex-col space-y-2">
            <router-link 
              v-for="item in menuItems" 
              :key="item.name"
              :to="item.path" 
              class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
              @click="mobileMenuOpen = false"
            >
              <component :is="item.icon" class="w-5 h-5 mr-3" />
              {{ item.label }}
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
    <main class="container-modern py-8">
      <router-view />
    </main>

    <!-- Toast notifications -->
    <div class="toast-container">
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        :class="['toast', toast.type]"
        class="animate-slide-in-right"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <component :is="getToastIcon(toast.type)" class="w-5 h-5 mr-3" />
            <span>{{ toast.message }}</span>
          </div>
          <button @click="removeToast(toast.id)" class="ml-4 text-gray-400 hover:text-gray-600">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  HomeIcon, 
  UsersIcon, 
  CurrencyDollarIcon, 
  CalendarDaysIcon, 
  MegaphoneIcon, 
  BellIcon,
  Bars3Icon,
  XMarkIcon,
  CheckCircleIcon,
  ExclamationTriangleIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'

// État réactif
const mobileMenuOpen = ref(false)
const notificationsCount = ref(3)
const toasts = ref([])

// Configuration du menu
const menuItems = [
  { name: 'dashboard', path: '/', label: 'Dashboard', icon: HomeIcon },
  { name: 'membres', path: '/membres', label: 'Membres', icon: UsersIcon },
  { name: 'cotisations', path: '/cotisations', label: 'Cotisations', icon: CurrencyDollarIcon },
  { name: 'activites', path: '/activites', label: 'Activités', icon: CalendarDaysIcon },
  { name: 'evenements', path: '/evenements', label: 'Événements', icon: MegaphoneIcon },
  { name: 'alertes', path: '/alertes', label: 'Alertes', icon: BellIcon },
]

// Fonctions utilitaires
const getToastIcon = (type) => {
  switch (type) {
    case 'success': return CheckCircleIcon
    case 'error': return XCircleIcon
    case 'warning': return ExclamationTriangleIcon
    default: return BellIcon
  }
}

const removeToast = (id) => {
  toasts.value = toasts.value.filter(toast => toast.id !== id)
}

const addToast = (message, type = 'info') => {
  const id = Date.now()
  toasts.value.push({ id, message, type })
  
  // Auto-remove après 5 secondes
  setTimeout(() => {
    removeToast(id)
  }, 5000)
}

// Exposer les fonctions globalement
window.addToast = addToast

// Initialisation
onMounted(() => {
  // Simuler des notifications
  setTimeout(() => {
    addToast('Bienvenue dans la plateforme Gestion Kourel !', 'success')
  }, 1000)
})
</script>

<style scoped>
.container-modern {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
}

@media (max-width: 768px) {
  .container-modern {
    padding: 0 16px;
  }
}

.animate-slide-in-right {
  animation: slideInRight 0.3s ease-out;
}
</style>

