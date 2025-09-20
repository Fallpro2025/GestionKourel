import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configuration de base pour les requêtes API
window.axios.defaults.baseURL = '/api';

// Intercepteur pour ajouter le token d'authentification
window.axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

// Intercepteur pour gérer les réponses
window.axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        if (error.response?.status === 401) {
            // Token expiré ou invalide
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            window.location.href = '/login';
        }
        
        if (error.response?.status === 429) {
            // Trop de requêtes
            window.addToast('Trop de requêtes. Veuillez patienter.', 'warning');
        }
        
        if (error.response?.status >= 500) {
            // Erreur serveur
            window.addToast('Erreur serveur. Veuillez réessayer.', 'error');
        }
        
        return Promise.reject(error);
    }
);

// Fonctions utilitaires pour l'authentification
window.auth = {
    login: async (email, password, remember = false) => {
        try {
            const response = await axios.post('/auth/login', {
                email,
                password,
                remember
            });
            
            const { token, membre } = response.data;
            localStorage.setItem('auth_token', token);
            localStorage.setItem('auth_user', JSON.stringify(membre));
            
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    
    logout: async () => {
        try {
            await axios.post('/auth/logout');
        } catch (error) {
            console.error('Erreur lors de la déconnexion:', error);
        } finally {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
        }
    },
    
    getCurrentUser: () => {
        const user = localStorage.getItem('auth_user');
        return user ? JSON.parse(user) : null;
    },
    
    isAuthenticated: () => {
        return !!localStorage.getItem('auth_token');
    },
    
    refreshToken: async () => {
        try {
            const response = await axios.post('/auth/refresh');
            const { token } = response.data;
            localStorage.setItem('auth_token', token);
            return token;
        } catch (error) {
            window.auth.logout();
            throw error;
        }
    }
};

// Fonctions utilitaires pour les notifications
window.notifications = {
    show: (message, type = 'info', duration = 5000) => {
        if (window.addToast) {
            window.addToast(message, type);
        } else {
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
    },
    
    success: (message) => window.notifications.show(message, 'success'),
    error: (message) => window.notifications.show(message, 'error'),
    warning: (message) => window.notifications.show(message, 'warning'),
    info: (message) => window.notifications.show(message, 'info')
};

// Fonctions utilitaires pour les formats
window.formatters = {
    currency: (amount, currency = 'XOF') => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency,
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    date: (dateString, options = {}) => {
        const defaultOptions = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        };
        return new Date(dateString).toLocaleDateString('fr-FR', { ...defaultOptions, ...options });
    },
    
    dateTime: (dateString) => {
        return new Date(dateString).toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    percentage: (value, decimals = 2) => {
        return `${parseFloat(value).toFixed(decimals)}%`;
    }
};

// Fonctions utilitaires pour les requêtes API
window.api = {
    get: (url, params = {}) => axios.get(url, { params }),
    post: (url, data = {}) => axios.post(url, data),
    put: (url, data = {}) => axios.put(url, data),
    delete: (url) => axios.delete(url),
    
    // Requêtes spécifiques
    dashboard: {
        getData: (periode = 30) => axios.get('/dashboard', { params: { periode } }),
        getStatistiques: (periode = 30) => axios.get('/dashboard/statistiques', { params: { periode } }),
        getAlertesCritiques: () => axios.get('/dashboard/alertes-critiques'),
        getActivitesEnCours: () => axios.get('/dashboard/activites-en-cours')
    },
    
    membres: {
        getAll: (params = {}) => axios.get('/membres', { params }),
        getById: (id) => axios.get(`/membres/${id}`),
        create: (data) => axios.post('/membres', data),
        update: (id, data) => axios.put(`/membres/${id}`, data),
        delete: (id) => axios.delete(`/membres/${id}`),
        search: (query) => axios.get('/membres/search', { params: { q: query } }),
        getStatistiques: (id) => axios.get(`/membres/${id}/statistiques`),
        getHistorique: (id, periode = 30) => axios.get(`/membres/${id}/historique`, { params: { periode } }),
        export: (id, format = 'pdf') => axios.get(`/membres/${id}/export`, { params: { format } })
    },
    
    activites: {
        getAll: (params = {}) => axios.get('/activites', { params }),
        getById: (id) => axios.get(`/activites/${id}`),
        create: (data) => axios.post('/activites', data),
        update: (id, data) => axios.put(`/activites/${id}`, data),
        delete: (id) => axios.delete(`/activites/${id}`),
        getPresences: (id) => axios.get(`/activites/${id}/presences`),
        marquerPresence: (id, data) => axios.post(`/activites/${id}/marquer-presence`, data),
        getStatistiques: (id) => axios.get(`/activites/${id}/statistiques`)
    },
    
    evenements: {
        getAll: (params = {}) => axios.get('/evenements', { params }),
        getById: (id) => axios.get(`/evenements/${id}`),
        create: (data) => axios.post('/evenements', data),
        update: (id, data) => axios.put(`/evenements/${id}`, data),
        delete: (id) => axios.delete(`/evenements/${id}`),
        getParticipants: (id) => axios.get(`/evenements/${id}/participants`),
        ajouterParticipant: (id, data) => axios.post(`/evenements/${id}/ajouter-participant`, data),
        retirerParticipant: (id, data) => axios.delete(`/evenements/${id}/retirer-participant`, { data }),
        getStatistiques: (id) => axios.get(`/evenements/${id}/statistiques`)
    },
    
    alertes: {
        getAll: (params = {}) => axios.get('/alertes', { params }),
        getById: (id) => axios.get(`/alertes/${id}`),
        create: (data) => axios.post('/alertes', data),
        update: (id, data) => axios.put(`/alertes/${id}`, data),
        delete: (id) => axios.delete(`/alertes/${id}`),
        marquerCommeLue: (id) => axios.post(`/alertes/${id}/marquer-lue`),
        resoudre: (id) => axios.post(`/alertes/${id}/resoudre`),
        getRegles: () => axios.get('/alertes/regles'),
        createRegle: (data) => axios.post('/alertes/regles', data),
        updateRegle: (id, data) => axios.put(`/alertes/regles/${id}`, data),
        deleteRegle: (id) => axios.delete(`/alertes/regles/${id}`)
    }
};

// Configuration globale de l'application
window.appConfig = {
    name: 'Gestion Kourel',
    version: '1.0.0',
    apiUrl: '/api',
    uploadMaxSize: 2048, // KB
    supportedImageTypes: ['jpeg', 'png', 'jpg', 'gif'],
    dateFormat: 'DD/MM/YYYY',
    timeFormat: 'HH:mm',
    currency: 'XOF',
    locale: 'fr-FR'
};

console.log('🚀 Gestion Kourel - Application initialisée');
console.log('📊 Dashboard moderne avec Vue.js 3 + Laravel');
console.log('🔐 Authentification sécurisée avec Sanctum');
console.log('📱 Interface responsive et moderne');