// Système d'alertes et confirmations modernes avec Tailwind CSS et Alpine.js
// Remplace complètement le système d'alertes précédent

class AlerteSystem {
    constructor() {
        this.container = null;
        this.modalContainer = null;
        this.init();
    }

    init() {
        // Vérifier que le DOM est prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.createContainers());
        } else {
            this.createContainers();
        }
    }

    createContainers() {
        try {
            // Vérifier si les conteneurs existent déjà
            if (document.getElementById('alerte-toast-container')) {
                this.container = document.getElementById('alerte-toast-container');
            } else {
                // Créer le conteneur pour les toasts
                this.container = document.createElement('div');
                this.container.id = 'alerte-toast-container';
                this.container.className = 'fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full';
                document.body.appendChild(this.container);
            }

            if (document.getElementById('alerte-modal-container')) {
                this.modalContainer = document.getElementById('alerte-modal-container');
            } else {
                // Créer le conteneur pour les modales de confirmation
                this.modalContainer = document.createElement('div');
                this.modalContainer.id = 'alerte-modal-container';
                this.modalContainer.className = 'fixed inset-0 z-50';
                document.body.appendChild(this.modalContainer);
            }
        } catch (error) {
            console.error('Erreur lors de la création des conteneurs d\'alertes:', error);
        }
    }

    // Méthode pour afficher un toast
    toast(message, type = 'info', options = {}) {
        try {
            if (!this.container) {
                this.createContainers();
            }
            
            if (!this.container) {
                console.error('Impossible de créer le conteneur de toast');
                return;
            }

            const toast = this.createToast(message, type, options);
            this.container.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);

            // Auto-suppression après 3 secondes
            if (options.autoClose !== false) {
                setTimeout(() => {
                    this.removeToast(toast);
                }, 3000);
            }
        } catch (error) {
            console.error('Erreur lors de l\'affichage du toast:', error);
        }
    }

    // Créer un toast
    createToast(message, type, options = {}) {
        const toast = document.createElement('div');
        toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0 bg-white rounded-lg shadow-lg border-l-4 p-4 ${this.getToastClasses(type)}`;
        
        const icon = this.getToastIcon(type);
        const title = this.getToastTitle(type);
        
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center ${this.getIconBgClasses(type)}">
                        <i class="${icon} text-sm ${this.getIconTextClasses(type)}"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-semibold text-gray-900">${title}</h4>
                    <p class="text-sm text-gray-600 mt-1">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="alerteSystem.removeToast(this.closest('.toast-item'))" 
                            class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        `;
        
        toast.classList.add('toast-item');
        return toast;
    }

    // Supprimer un toast
    removeToast(toast) {
        if (!toast) return;
        
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    // Méthode pour afficher une confirmation
    confirmation(message, callback, options = {}) {
        try {
            if (!this.modalContainer) {
                this.createContainers();
            }
            
            if (!this.modalContainer) {
                console.error('Impossible de créer le conteneur de modale');
                return;
            }

            const modal = this.createConfirmationModal(message, callback, options);
            this.modalContainer.appendChild(modal);
            
            // Animation d'entrée
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
        } catch (error) {
            console.error('Erreur lors de l\'affichage de la confirmation:', error);
        }
    }

    // Créer une modale de confirmation
    createConfirmationModal(message, callback, options = {}) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300';
        
        const title = options.title || 'Confirmation';
        const confirmText = options.confirmText || 'Confirmer';
        const cancelText = options.cancelText || 'Annuler';
        const type = options.type || 'warning';
        
        modal.innerHTML = `
            <div class="modal-content bg-white rounded-xl shadow-2xl max-w-md w-full transform scale-95 transition-transform duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center ${this.getIconBgClasses(type)} mr-3">
                            <i class="${this.getToastIcon(type)} text-lg ${this.getIconTextClasses(type)}"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                    </div>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex space-x-3 justify-end">
                        <button onclick="alerteSystem.closeConfirmation(this.closest('.confirmation-modal'), false)" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            ${cancelText}
                        </button>
                        <button onclick="alerteSystem.closeConfirmation(this.closest('.confirmation-modal'), true)" 
                                class="px-4 py-2 text-sm font-medium text-white ${this.getConfirmButtonClasses(type)} rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 ${this.getConfirmFocusClasses(type)}">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.add('confirmation-modal');
        modal.callback = callback;
        return modal;
    }

    // Fermer une modale de confirmation
    closeConfirmation(modal, confirmed) {
        if (!modal) return;
        
        modal.classList.add('opacity-0');
        modal.querySelector('.modal-content').classList.add('scale-95');
        
        setTimeout(() => {
            if (modal.parentNode) {
                modal.parentNode.removeChild(modal);
            }
            if (modal.callback) {
                modal.callback(confirmed);
            }
        }, 300);
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

    // Méthodes utilitaires pour les classes CSS
    getToastClasses(type) {
        const classes = {
            success: 'border-green-500',
            error: 'border-red-500',
            warning: 'border-yellow-500',
            info: 'border-blue-500'
        };
        return classes[type] || classes.info;
    }

    getToastIcon(type) {
        const icons = {
            success: 'fas fa-check',
            error: 'fas fa-times',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    getToastTitle(type) {
        const titles = {
            success: 'Succès',
            error: 'Erreur',
            warning: 'Attention',
            info: 'Information'
        };
        return titles[type] || titles.info;
    }

    getIconBgClasses(type) {
        const classes = {
            success: 'bg-green-100',
            error: 'bg-red-100',
            warning: 'bg-yellow-100',
            info: 'bg-blue-100'
        };
        return classes[type] || classes.info;
    }

    getIconTextClasses(type) {
        const classes = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            info: 'text-blue-600'
        };
        return classes[type] || classes.info;
    }

    getConfirmButtonClasses(type) {
        const classes = {
            success: 'bg-green-600 hover:bg-green-700',
            error: 'bg-red-600 hover:bg-red-700',
            warning: 'bg-yellow-600 hover:bg-yellow-700',
            info: 'bg-blue-600 hover:bg-blue-700'
        };
        return classes[type] || classes.info;
    }

    getConfirmFocusClasses(type) {
        const classes = {
            success: 'focus:ring-green-500',
            error: 'focus:ring-red-500',
            warning: 'focus:ring-yellow-500',
            info: 'focus:ring-blue-500'
        };
        return classes[type] || classes.info;
    }
}

// Initialisation du système
let alerteSystem = null;

function initAlerteSystem() {
    try {
        if (!alerteSystem) {
            alerteSystem = new AlerteSystem();
            
            // Rendre disponible globalement
            window.alerteSystem = alerteSystem;
            window.alerteModerne = alerteSystem; // Compatibilité avec l'ancien système
            
            console.log('Système d\'alertes initialisé avec succès');
        }
    } catch (error) {
        console.error('Erreur lors de l\'initialisation du système d\'alertes:', error);
    }
}

// Initialiser dès que possible
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAlerteSystem);
} else {
    // Attendre un peu pour s'assurer que tout est prêt
    setTimeout(initAlerteSystem, 100);
}

// Gestion des touches pour les modales de confirmation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        try {
            const modals = document.querySelectorAll('.confirmation-modal');
            modals.forEach(modal => {
                if (alerteSystem && typeof alerteSystem.closeConfirmation === 'function') {
                    alerteSystem.closeConfirmation(modal, false);
                }
            });
        } catch (error) {
            console.error('Erreur lors de la fermeture des modales:', error);
        }
    }
});

// Export pour utilisation dans d'autres modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AlerteSystem;
}
