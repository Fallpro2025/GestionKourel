import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Dashboard from './components/Dashboard.vue'
import Membres from './components/Membres.vue'
import Cotisations from './components/Cotisations.vue'
import Activites from './components/Activites.vue'
import Evenements from './components/Evenements.vue'
import Alertes from './components/Alertes.vue'
import './bootstrap'

// Configuration des routes
const routes = [
  { path: '/', component: Dashboard, name: 'dashboard' },
  { path: '/membres', component: Membres, name: 'membres' },
  { path: '/cotisations', component: Cotisations, name: 'cotisations' },
  { path: '/activites', component: Activites, name: 'activites' },
  { path: '/evenements', component: Evenements, name: 'evenements' },
  { path: '/alertes', component: Alertes, name: 'alertes' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Création de l'application Vue
const app = createApp(App)
app.use(router)
app.mount('#app')