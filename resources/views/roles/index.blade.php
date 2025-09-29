@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Rôles')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-white"><i class="fas fa-user-tag mr-3"></i>Gestion des Rôles</h1>
                    <span class="ml-3 px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full border border-blue-500/30">
                        {{ $roles->count() }} rôles
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="ouvrirModalAjout()" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-plus mr-2"></i>Nouveau Rôle
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages de session gérés par le système de toast -->
        @include('components.alertes-session')
        
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500/20 rounded-full">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-white/70">Total des Rôles</p>
                        <p class="text-2xl font-bold text-white">{{ $roles->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500/20 rounded-full">
                        <i class="fas fa-user-check text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-white/70">Rôles Actifs</p>
                        <p class="text-2xl font-bold text-white">{{ $roles->where('niveau_priorite', '>', 0)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500/20 rounded-full">
                        <i class="fas fa-crown text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-white/70">Niveau Max</p>
                        <p class="text-2xl font-bold text-white">{{ $roles->max('niveau_priorite') ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des rôles -->
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg overflow-hidden border border-white/20">
            <div class="px-6 py-4 bg-white/5 border-b border-white/20">
                <h2 class="text-xl font-semibold text-white">Liste des Rôles</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Priorité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Membres</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/5 divide-y divide-white/10">
                        @forelse($roles as $role)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($role->nom, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $role->nom }}</div>
                                        <div class="text-sm text-white/60">Créé {{ $role->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white max-w-xs truncate">{{ $role->description ?? 'Aucune description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($role->niveau_priorite >= 4) bg-red-500/20 text-red-400 border border-red-500/30
                                    @elseif($role->niveau_priorite >= 3) bg-orange-500/20 text-orange-400 border border-orange-500/30
                                    @elseif($role->niveau_priorite >= 2) bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                    @else bg-green-500/20 text-green-400 border border-green-500/30
                                    @endif">
                                    Niveau {{ $role->niveau_priorite }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    {{ $role->membres_count }} membre{{ $role->membres_count > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">
                                    {{ count($role->permissions ?? []) }} permission{{ count($role->permissions ?? []) > 1 ? 's' : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="voirRole({{ $role->id }})" 
                                            class="text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="modifierRole({{ $role->id }})" 
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="supprimerRole({{ $role->id }}, '{{ $role->nom }}')" 
                                            class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                            {{ $role->membres_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-white/60">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun rôle trouvé</p>
                                    <p class="text-sm">Commencez par créer votre premier rôle</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajout/Modification -->
<div id="modalRole" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-11/12 md:w-3/4 lg:w-1/2">
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500/20 to-purple-500/20 border-b border-white/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800" id="modalTitre">Nouveau Rôle</h3>
                    <button onclick="fermerModal()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="formRole" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-gray-800 mb-2">Nom du rôle *</label>
                        <input type="text" id="nom" name="nom" required 
                               class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200"
                               placeholder="Ex: Administrateur, Membre, Trésorier...">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200"
                                  placeholder="Décrivez les responsabilités de ce rôle..."></textarea>
                    </div>
                    
                    <div>
                        <label for="niveau_priorite" class="block text-sm font-semibold text-gray-800 mb-2">Niveau de priorité *</label>
                        <select id="niveau_priorite" name="niveau_priorite" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 transition-all duration-200">
                            <option value="1">1 - Bas (Membre standard)</option>
                            <option value="2">2 - Moyen (Responsable section)</option>
                            <option value="3">3 - Élevé (Coordinateur)</option>
                            <option value="4">4 - Très élevé (Direction)</option>
                            <option value="5">5 - Administrateur (Accès complet)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Permissions *</label>
                        <div class="bg-blue-50/80 rounded-xl p-4 border border-blue-200/50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto" id="permissionsContainer">
                                <!-- Les permissions seront ajoutées dynamiquement -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="fermerModal()" 
                                class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-500/25">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div id="modalDetails" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-11/12 md:w-3/4 lg:w-1/2">
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-500/20 to-pink-500/20 border-b border-white/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Détails du Rôle</h3>
                    <button onclick="fermerModalDetails()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="contenuDetails" class="space-y-4">
                    <!-- Contenu dynamique -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Permissions disponibles
const permissionsDisponibles = {
    'voir_profil': 'Voir les profils des membres',
    'gestion_membres': 'Gérer les membres',
    'gestion_cotisations': 'Gérer les cotisations',
    'gestion_evenements': 'Gérer les événements',
    'gestion_finances': 'Gérer les finances',
    'gestion_documents': 'Gérer les documents',
    'gestion_activites': 'Gérer les activités',
    'animer_activites': 'Animer les activités',
    'participer_chorale': 'Participer à la chorale',
    'participation_repetitions': 'Participer aux répétitions',
    'participation_concerts': 'Participer aux concerts',
    'interpretation_solos': 'Interpréter des solos',
    'interpretation_instrumentale': 'Interpréter des instruments',
    'gestion_section': 'Gérer une section',
    'coordination_activites': 'Coordonner les activités',
    'gestion_technique': 'Gérer la technique',
    'maintenance_equipements': 'Maintenir les équipements',
    'administration_generale': 'Administration générale'
};

let roleActuel = null;

// Ouvrir modal d'ajout
function ouvrirModalAjout() {
    document.getElementById('modalTitre').textContent = 'Nouveau Rôle';
    document.getElementById('formRole').reset();
    document.getElementById('formRole').action = '/roles';
    document.getElementById('formRole').method = 'POST';
    roleActuel = null;
    
    // Ajouter les permissions
    const containerPermissions = document.getElementById('permissionsContainer');
    containerPermissions.innerHTML = '';
    
    Object.entries(permissionsDisponibles).forEach(([key, label]) => {
        const div = document.createElement('div');
        div.className = 'flex items-center p-2 bg-white/50 rounded-lg hover:bg-white/70 transition-colors duration-200';
        div.innerHTML = `
            <input type="checkbox" id="perm_${key}" name="permissions[]" value="${key}" 
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="perm_${key}" class="ml-3 text-sm text-gray-800 font-medium cursor-pointer">${label}</label>
        `;
        containerPermissions.appendChild(div);
    });
    
    document.getElementById('modalRole').classList.remove('hidden');
}

// Modifier un rôle
function modifierRole(roleId) {
    // Récupérer les données du rôle via AJAX
    fetch(`/roles/${roleId}/edit`)
        .then(response => response.text())
        .then(html => {
            // Parser la réponse et remplir le formulaire
            // Pour simplifier, on va juste ouvrir le modal avec les données
            document.getElementById('modalTitre').textContent = 'Modifier le Rôle';
            document.getElementById('formRole').action = `/roles/${roleId}`;
            document.getElementById('formRole').method = 'PUT';
            roleActuel = roleId;
            
            // Ajouter les permissions
            const containerPermissions = document.getElementById('permissionsContainer');
            containerPermissions.innerHTML = '';
            
            Object.entries(permissionsDisponibles).forEach(([key, label]) => {
                const div = document.createElement('div');
                div.className = 'flex items-center p-2 bg-white/50 rounded-lg hover:bg-white/70 transition-colors duration-200';
                div.innerHTML = `
                    <input type="checkbox" id="perm_${key}" name="permissions[]" value="${key}" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="perm_${key}" class="ml-3 text-sm text-gray-800 font-medium cursor-pointer">${label}</label>
                `;
                containerPermissions.appendChild(div);
            });
            
            document.getElementById('modalRole').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.error('Erreur lors du chargement du rôle');
            } else {
                alert('Erreur lors du chargement du rôle');
            }
        });
}

// Voir les détails d'un rôle
function voirRole(roleId) {
    fetch(`/roles/${roleId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenuDetails').innerHTML = html;
            document.getElementById('modalDetails').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.error('Erreur lors du chargement des détails');
            } else {
                alert('Erreur lors du chargement des détails');
            }
        });
}

// Supprimer un rôle
function supprimerRole(roleId, nomRole) {
    if (typeof alerteModerne !== 'undefined' && alerteModerne) {
        alerteModerne.confirmation(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" ?`, function(confirme) {
            if (confirme) {
                supprimerRoleAction(roleId);
            }
        });
    } else {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" ?`)) {
            supprimerRoleAction(roleId);
        }
    }
}

function supprimerRoleAction(roleId) {
    fetch(`/roles/${roleId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            location.reload();
        } else {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.error('Erreur lors de la suppression');
        } else {
            alert('Erreur lors de la suppression');
        }
    });
}

// Fermer les modals
function fermerModal() {
    document.getElementById('modalRole').classList.add('hidden');
}

function fermerModalDetails() {
    document.getElementById('modalDetails').classList.add('hidden');
}

// Gestion du formulaire
document.getElementById('formRole').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const permissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked')).map(cb => cb.value);
    
    const data = {
        nom: formData.get('nom'),
        description: formData.get('description'),
        niveau_priorite: parseInt(formData.get('niveau_priorite')),
        permissions: permissions
    };
    
    const url = this.action;
    const method = this.method;
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.success(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            fermerModal();
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.error(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteModerne !== 'undefined' && alerteModerne) {
            alerteModerne.error('Erreur lors de l\'enregistrement');
        } else {
            alert('Erreur lors de l\'enregistrement');
        }
    });
});
</script>
@endsection
