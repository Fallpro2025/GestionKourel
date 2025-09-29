// Système d'alertes moderne avec glassmorphism
console.log('Chargement du système d\'alertes moderne...');

class AlerteModerne {
    constructor() {
        this.container = null;
        this.modalContainer = null;
        this.init();
    }

    init() {
        // Attendre que le DOM soit prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.createContainers());
        } else {
            this.createContainers();
        }
    }

    createContainers() {
        try {
            // Créer le conteneur pour les toasts
            if (!document.getElementById('alerte-toast-container')) {
                this.container = document.createElement('div');
                this.container.id = 'alerte-toast-container';
                this.container.className = 'fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full pointer-events-none';
                document.body.appendChild(this.container);
            }

            // Créer le conteneur pour les modals
            if (!document.getElementById('alerte-modal-container')) {
                this.modalContainer = document.createElement('div');
                this.modalContainer.id = 'alerte-modal-container';
                this.modalContainer.className = 'fixed inset-0 z-50 pointer-events-none';
                document.body.appendChild(this.modalContainer);
            }
            
            console.log('Conteneurs d\'alertes créés avec succès');
        } catch (error) {
            console.error('Erreur lors de la création des conteneurs:', error);
        }
    }

    // Afficher un toast moderne
    toast(message, type = 'info', options = {}) {
        try {
            if (!this.container) {
                this.createContainers();
            }

            const toast = this.createToast(message, type, options);
            this.container.appendChild(toast);

            // Animation d'entrée
            setTimeout(() => {
                toast.style.transform = 'translateX(0) scale(1)';
                toast.style.opacity = '1';
            }, 10);

            // Auto-suppression
            if (options.autoClose !== false) {
                setTimeout(() => {
                    this.removeToast(toast);
                }, options.duration || 4000);
            }
        } catch (error) {
            console.error('Erreur lors de l\'affichage du toast:', error);
        }
    }

    // Créer un toast moderne avec glassmorphism
    createToast(message, type, options = {}) {
        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto transform transition-all duration-500 ease-out';
        toast.style.transform = 'translateX(100%) scale(0.9)';
        toast.style.opacity = '0';

        // Configuration des couleurs
        const configs = {
            success: {
                bg: 'bg-green-500/10',
                border: 'border-green-500/30',
                iconBg: 'bg-green-500/20',
                iconColor: 'text-green-400',
                icon: 'fas fa-check-circle',
                title: 'Succès',
                textColor: 'text-green-300'
            },
            error: {
                bg: 'bg-red-500/10',
                border: 'border-red-500/30',
                iconBg: 'bg-red-500/20',
                iconColor: 'text-red-400',
                icon: 'fas fa-exclamation-circle',
                title: 'Erreur',
                textColor: 'text-red-300'
            },
            warning: {
                bg: 'bg-yellow-500/10',
                border: 'border-yellow-500/30',
                iconBg: 'bg-yellow-500/20',
                iconColor: 'text-yellow-400',
                icon: 'fas fa-exclamation-triangle',
                title: 'Attention',
                textColor: 'text-yellow-300'
            },
            info: {
                bg: 'bg-blue-500/10',
                border: 'border-blue-500/30',
                iconBg: 'bg-blue-500/20',
                iconColor: 'text-blue-400',
                icon: 'fas fa-info-circle',
                title: 'Information',
                textColor: 'text-blue-300'
            }
        };

        const config = configs[type] || configs.info;
        const title = options.title || config.title;

        toast.innerHTML = `
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border ${config.border} p-4 ${config.bg}">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center ${config.iconBg}">
                            <i class="${config.icon} text-lg ${config.iconColor}"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-bold text-white">${title}</h4>
                        <p class="text-sm ${config.textColor} mt-1 leading-relaxed">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button onclick="alerteModerne.removeToast(this.closest('.toast-item'))" 
                                class="inline-flex text-white/60 hover:text-white focus:outline-none focus:text-white transition-colors duration-200">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        toast.classList.add('toast-item');
        return toast;
    }

    // Supprimer un toast avec animation
    removeToast(toast) {
        if (!toast) return;
        
        toast.style.transform = 'translateX(100%) scale(0.9)';
        toast.style.opacity = '0';
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }

    // Méthodes de raccourci
    success(message, options = {}) {
        this.toast(message, 'success', options);
    }

    error(message, options = {}) {
        this.toast(message, 'error', options);
    }

    warning(message, options = {}) {
        this.toast(message, 'warning', options);
    }

    info(message, options = {}) {
        this.toast(message, 'info', options);
    }

    // Confirmation moderne avec glassmorphism
    confirmation(message, callback, options = {}) {
        try {
            if (!this.modalContainer) {
                this.createContainers();
            }

            const modal = this.createConfirmationModal(message, callback, options);
            this.modalContainer.appendChild(modal);

            // Animation d'entrée
            setTimeout(() => {
                modal.style.opacity = '1';
                const content = modal.querySelector('.modal-content');
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);

        } catch (error) {
            console.error('Erreur lors de l\'affichage de la confirmation:', error);
            // Fallback vers confirm natif
            const confirmed = confirm(message);
            if (callback) callback(confirmed);
        }
    }

    // Créer un modal de confirmation moderne
    createConfirmationModal(message, callback, options = {}) {
        const modal = document.createElement('div');
        modal.className = 'pointer-events-auto fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
        modal.style.opacity = '0';
        modal.style.transition = 'opacity 0.3s ease-out';

        modal.innerHTML = `
            <div class="modal-content bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 p-6 max-w-md w-full transform transition-all duration-300 ease-out" 
                 style="transform: scale(0.9); opacity: 0;">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-500/20 mb-4">
                        <i class="fas fa-question-circle text-purple-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Confirmation</h3>
                    <p class="text-sm text-gray-600 mb-6">${message}</p>
                    <div class="flex space-x-3 justify-center">
                        <button class="btn-cancel px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                            Annuler
                        </button>
                        <button class="btn-confirm px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-purple-500/25">
                            Confirmer
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Gestion des événements
        const btnCancel = modal.querySelector('.btn-cancel');
        const btnConfirm = modal.querySelector('.btn-confirm');

        const closeModal = (result) => {
            modal.style.opacity = '0';
            const content = modal.querySelector('.modal-content');
            content.style.transform = 'scale(0.9)';
            content.style.opacity = '0';
            
            setTimeout(() => {
                if (modal.parentNode) {
                    modal.parentNode.removeChild(modal);
                }
                if (callback) callback(result);
            }, 300);
        };

        btnCancel.addEventListener('click', () => closeModal(false));
        btnConfirm.addEventListener('click', () => closeModal(true));
        
        // Fermer en cliquant sur le fond
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(false);
            }
        });

        // Fermer avec Escape
        const handleKeydown = (e) => {
            if (e.key === 'Escape') {
                closeModal(false);
                document.removeEventListener('keydown', handleKeydown);
            }
        };
        document.addEventListener('keydown', handleKeydown);

        return modal;
    }
}

// Initialisation
let alerteModerne = null;

function initAlerteModerne() {
    try {
        if (!alerteModerne) {
            alerteModerne = new AlerteModerne();
            window.alerteModerne = alerteModerne;
            window.alerteSystem = alerteModerne; // Compatibilité
            console.log('Système d\'alertes moderne initialisé');
        }
    } catch (error) {
        console.error('Erreur lors de l\'initialisation:', error);
    }
}

// Initialiser
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAlerteModerne);
} else {
    setTimeout(initAlerteModerne, 100);
}