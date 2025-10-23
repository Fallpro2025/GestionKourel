@props(['roles' => [], 'statuts' => ['actif', 'inactif', 'suspendu']])

<div class="recherche-avancee-container">
    <!-- Bouton pour ouvrir/fermer la recherche avancée -->
    <div class="mb-4">
        <button onclick="toggleRechercheAvancee()" 
                class="px-4 py-2 bg-gray-500/20 text-gray-400 font-medium rounded-xl hover:bg-gray-500/30 transition-all duration-300 border border-gray-500/30 flex items-center space-x-2">
            <i class="fas fa-search-plus"></i>
            <span>Recherche avancée</span>
            <i class="fas fa-chevron-down transition-transform duration-200" id="chevronRecherche"></i>
        </button>
    </div>

    <!-- Panneau de recherche avancée -->
    <div id="panneauRechercheAvancee" class="hidden bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20 mb-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <i class="fas fa-filter mr-3 text-blue-400"></i>
            Filtres avancés
        </h3>

        <form id="formRechercheAvancee" class="space-y-4">
            <!-- Ligne 1: Recherche textuelle -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="rechercheNom" class="block text-sm font-medium text-white/70 mb-2">Nom</label>
                    <input type="text" 
                           id="rechercheNom" 
                           name="nom" 
                           placeholder="Rechercher par nom..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="recherchePrenom" class="block text-sm font-medium text-white/70 mb-2">Prénom</label>
                    <input type="text" 
                           id="recherchePrenom" 
                           name="prenom" 
                           placeholder="Rechercher par prénom..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Ligne 2: Email et téléphone -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="rechercheEmail" class="block text-sm font-medium text-white/70 mb-2">Email</label>
                    <input type="email" 
                           id="rechercheEmail" 
                           name="email" 
                           placeholder="Rechercher par email..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="rechercheTelephone" class="block text-sm font-medium text-white/70 mb-2">Téléphone</label>
                    <input type="text" 
                           id="rechercheTelephone" 
                           name="telephone" 
                           placeholder="Rechercher par téléphone..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Ligne 3: Rôle et statut -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="rechercheRole" class="block text-sm font-medium text-white/70 mb-2">Rôle</label>
                    <select id="rechercheRole" 
                            name="role_id" 
                            class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" class="text-gray-800">{{ $role->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="rechercheStatut" class="block text-sm font-medium text-white/70 mb-2">Statut</label>
                    <select id="rechercheStatut" 
                            name="statut" 
                            class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $statut)
                        <option value="{{ $statut }}" class="text-gray-800">{{ ucfirst($statut) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Ligne 4: Profession et matricule -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="rechercheProfession" class="block text-sm font-medium text-white/70 mb-2">Profession</label>
                    <input type="text" 
                           id="rechercheProfession" 
                           name="profession" 
                           placeholder="Rechercher par profession..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="rechercheMatricule" class="block text-sm font-medium text-white/70 mb-2">Matricule</label>
                    <input type="text" 
                           id="rechercheMatricule" 
                           name="matricule" 
                           placeholder="Rechercher par matricule..."
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Ligne 5: Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="dateAdhesionDebut" class="block text-sm font-medium text-white/70 mb-2">Date d'adhésion (début)</label>
                    <input type="date" 
                           id="dateAdhesionDebut" 
                           name="date_adhesion_debut" 
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="dateAdhesionFin" class="block text-sm font-medium text-white/70 mb-2">Date d'adhésion (fin)</label>
                    <input type="date" 
                           id="dateAdhesionFin" 
                           name="date_adhesion_fin" 
                           class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t border-white/20">
                <div class="flex space-x-2">
                    <button type="button" 
                            onclick="rechercherMembres()" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Rechercher</span>
                    </button>
                    <button type="button" 
                            onclick="reinitialiserRecherche()" 
                            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-undo"></i>
                        <span>Réinitialiser</span>
                    </button>
                </div>
                
                <div class="text-sm text-white/60">
                    <span id="nombreResultats">0</span> résultat(s) trouvé(s)
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let rechercheAvanceeOuverte = false;

// Fonction pour ouvrir/fermer la recherche avancée
function toggleRechercheAvancee() {
    const panneau = document.getElementById('panneauRechercheAvancee');
    const chevron = document.getElementById('chevronRecherche');
    
    if (rechercheAvanceeOuverte) {
        panneau.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
        rechercheAvanceeOuverte = false;
    } else {
        panneau.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
        rechercheAvanceeOuverte = true;
    }
}

// Fonction pour rechercher les membres
async function rechercherMembres() {
    const form = document.getElementById('formRechercheAvancee');
    const formData = new FormData(form);
    
    // Convertir en objet
    const params = {};
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params[key] = value;
        }
    }
    
    try {
        // Construire l'URL avec les paramètres
        const url = new URL('/membres/recherche-avancee', window.location.origin);
        Object.keys(params).forEach(key => {
            url.searchParams.append(key, params[key]);
        });
        
        const response = await fetch(url);
        const data = await response.json();
        
        // Mettre à jour l'affichage des membres
        if (typeof mettreAJourAffichageMembres === 'function') {
            mettreAJourAffichageMembres(data.data);
        }
        
        // Mettre à jour le nombre de résultats
        document.getElementById('nombreResultats').textContent = data.total || 0;
        
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.succes(`${data.total || 0} membre(s) trouvé(s)`);
        }
        
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        if (typeof alerteModerne !== 'undefined') {
            alerteModerne.erreur('Erreur lors de la recherche');
        }
    }
}

// Fonction pour réinitialiser la recherche
function reinitialiserRecherche() {
    const form = document.getElementById('formRechercheAvancee');
    form.reset();
    
    // Recharger tous les membres
    if (typeof rechargerTousMembres === 'function') {
        rechargerTousMembres();
    }
    
    document.getElementById('nombreResultats').textContent = '0';
    
    if (typeof alerteModerne !== 'undefined') {
        alerteModerne.information('Recherche réinitialisée');
    }
}

// Recherche en temps réel sur les champs de texte
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('#formRechercheAvancee input[type="text"], #formRechercheAvancee input[type="email"]');
    
    inputs.forEach(input => {
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    rechercherMembres();
                }
            }, 500);
        });
    });
    
    // Recherche immédiate sur les selects
    const selects = document.querySelectorAll('#formRechercheAvancee select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            rechercherMembres();
        });
    });
});
</script>
