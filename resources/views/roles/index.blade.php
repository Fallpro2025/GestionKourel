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
                                @php($perms = is_countable($role->permissions ?? null) ? $role->permissions : [])
                                @php($totalPerms = count($perms))
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach(array_slice($perms, 0, 3) as $perm)
                                        @php($label = ucwords(str_replace('_', ' ', $perm)))
                                        <span class="px-2.5 py-1 text-[11px] font-medium rounded-full bg-gradient-to-r from-blue-500/20 to-indigo-500/20 text-blue-200 border border-blue-400/30 hover:from-blue-500/30 hover:to-indigo-500/30 transition-smooth">
                                            <i class="fas fa-check-circle mr-1 text-blue-300"></i>{{ $label }}
                                        </span>
                                    @endforeach
                                    @if($totalPerms > 3)
                                        <button onclick="voirRole({{ $role->id }})" class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/15 transition-smooth">
                                            +{{ $totalPerms - 3 }}
                                        </button>
                                    @endif
                                    @if($totalPerms === 0)
                                        <span class="text-sm text-white/60">Aucune permission</span>
                                    @endif
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
<div id="modalRole" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-[2200]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 max-w-2xl w-full border border-white/20 transform transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-white" id="modalTitre">Nouveau Rôle</h3>
                <button onclick="fermerModal()" class="text-white/60 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
            </div>
            
                <form id="formRole" class="space-y-6">
                    @csrf
                    <div>
                    <label for="nom" class="block text-sm font-semibold text-white/80 mb-2">Nom du rôle <span class="text-red-400">*</span></label>
                        <input type="text" id="nom" name="nom" required 
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50 transition-all duration-200"
                               placeholder="Ex: Administrateur, Membre, Trésorier...">
                    </div>
                    
                    <div>
                    <label for="description" class="block text-sm font-semibold text-white/80 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-white/50 transition-all duration-200"
                                  placeholder="Décrivez les responsabilités de ce rôle..."></textarea>
                    </div>
                    
                    <div>
                    <label for="niveau_priorite" class="block text-sm font-semibold text-white/80 mb-2">Niveau de priorité <span class="text-red-400">*</span></label>
                        <select id="niveau_priorite" name="niveau_priorite" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white transition-all duration-200">
                        <option value="1" class="text-gray-800">1 - Bas (Membre standard)</option>
                        <option value="2" class="text-gray-800">2 - Moyen (Responsable section)</option>
                        <option value="3" class="text-gray-800">3 - Élevé (Coordinateur)</option>
                        <option value="4" class="text-gray-800">4 - Très élevé (Direction)</option>
                        <option value="5" class="text-gray-800">5 - Administrateur (Accès complet)</option>
                        </select>
                    </div>
                    
                    <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-white/80">Permissions <span class="text-red-400">*</span></label>
                        <div class="flex space-x-2">
                            <button type="button" onclick="toutCocher()" 
                                    class="px-3 py-1 text-xs font-medium text-green-400 bg-green-500/20 hover:bg-green-500/30 rounded-lg transition-all duration-200 border border-green-500/30">
                                <i class="fas fa-check-double mr-1"></i>Tout cocher
                            </button>
                            <button type="button" onclick="toutDecocher()" 
                                    class="px-3 py-1 text-xs font-medium text-red-400 bg-red-500/20 hover:bg-red-500/30 rounded-lg transition-all duration-200 border border-red-500/30">
                                <i class="fas fa-times mr-1"></i>Tout décocher
                            </button>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto" id="permissionsContainer">
                                <!-- Les permissions seront ajoutées dynamiquement -->
                            </div>
                        </div>
                    </div>
                    
                <div class="flex justify-end space-x-3 pt-4 border-t border-white/20">
                        <button type="button" onclick="fermerModal()" 
                            class="px-6 py-3 text-sm font-medium text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-200 border border-white/20">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-blue-400 bg-blue-500/20 hover:bg-blue-500/30 rounded-xl transition-all duration-200 border border-blue-500/30">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal Détails (style unifié) -->
<div id="modalDetails" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[2200] hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-white/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Détails du Rôle</h3>
                    <button onclick="fermerModalDetails()" class="text-white/70 hover:text-white transition-smooth">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="contenuDetails" class="space-y-4 text-white">
                    <!-- Contenu dynamique -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Données des rôles
const rolesData = {!! json_encode($roles->map(function($role) {
    return [
        'id' => $role->id,
        'nom' => $role->nom,
        'description' => $role->description,
        'niveau_priorite' => $role->niveau_priorite,
        'permissions' => $role->permissions ?? [],
        'membres_count' => $role->membres_count,
        'created_at' => $role->created_at->format('d/m/Y à H:i'),
        'updated_at' => $role->updated_at->format('d/m/Y à H:i')
    ];
})) !!};

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

// Fonction pour tout cocher
function toutCocher() {
    const checkboxes = document.querySelectorAll('#permissionsContainer input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

// Fonction pour tout décocher
function toutDecocher() {
    const checkboxes = document.querySelectorAll('#permissionsContainer input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

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
        div.className = 'flex items-center p-3 bg-white/10 rounded-lg hover:bg-white/15 transition-colors duration-200 border border-white/10';
        div.innerHTML = `
            <input type="checkbox" id="perm_${key}" name="permissions[]" value="${key}" 
                   class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-white/30 rounded bg-white/10">
            <label for="perm_${key}" class="ml-3 text-sm text-white font-medium cursor-pointer">${label}</label>
        `;
        containerPermissions.appendChild(div);
    });
    
    document.getElementById('modalRole').classList.remove('hidden');
}

// Modifier un rôle
function modifierRole(roleId) {
    const role = rolesData.find(r => r.id === roleId);
    if (!role) {
        if (typeof alerteModerne !== 'undefined' && alerteModerne) {
            alerteModerne.error('Rôle introuvable');
        } else {
            alert('Rôle introuvable');
        }
        return;
    }
    
    // Configurer le formulaire en mode modification
            document.getElementById('modalTitre').textContent = 'Modifier le Rôle';
            document.getElementById('formRole').action = `/roles/${roleId}`;
    document.getElementById('formRole').dataset.method = 'PUT';
            roleActuel = roleId;
            
    // Remplir les champs du formulaire
    document.getElementById('nom').value = role.nom;
    document.getElementById('description').value = role.description || '';
    document.getElementById('niveau_priorite').value = role.niveau_priorite;
    
    // Ajouter les permissions avec les bonnes cases cochées
            const containerPermissions = document.getElementById('permissionsContainer');
            containerPermissions.innerHTML = '';
            
            Object.entries(permissionsDisponibles).forEach(([key, label]) => {
                const div = document.createElement('div');
        div.className = 'flex items-center p-3 bg-white/10 rounded-lg hover:bg-white/15 transition-colors duration-200 border border-white/10';
        const isChecked = role.permissions && role.permissions.includes(key) ? 'checked' : '';
                div.innerHTML = `
            <input type="checkbox" id="perm_${key}" name="permissions[]" value="${key}" ${isChecked}
                   class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-white/30 rounded bg-white/10">
            <label for="perm_${key}" class="ml-3 text-sm text-white font-medium cursor-pointer">${label}</label>
                `;
                containerPermissions.appendChild(div);
            });
            
            document.getElementById('modalRole').classList.remove('hidden');
}

// Voir les détails d'un rôle
function voirRole(roleId) {
    const role = rolesData.find(r => r.id === roleId);
    if (!role) {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
            alerteModerne.error('Rôle introuvable');
        } else {
            alert('Rôle introuvable');
        }
        return;
    }
    
    // Générer le badge de priorité
    let prioriteBadge = '';
    if (role.niveau_priorite >= 4) {
        prioriteBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">Niveau ' + role.niveau_priorite + '</span>';
    } else if (role.niveau_priorite >= 3) {
        prioriteBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30">Niveau ' + role.niveau_priorite + '</span>';
    } else if (role.niveau_priorite >= 2) {
        prioriteBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Niveau ' + role.niveau_priorite + '</span>';
            } else {
        prioriteBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">Niveau ' + role.niveau_priorite + '</span>';
    }
    
    // Générer la liste des permissions
    let permissionsHtml = '';
    if (role.permissions && role.permissions.length > 0) {
        permissionsHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-2">';
        role.permissions.forEach(perm => {
            const label = permissionsDisponibles[perm] || perm.replace(/_/g, ' ');
            permissionsHtml += `
                <div class="flex items-center p-2 bg-white/10 rounded-lg border border-white/10">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    <span class="text-sm text-white">${label}</span>
                </div>
            `;
        });
        permissionsHtml += '</div>';
    } else {
        permissionsHtml = '<p class="text-white/60 text-sm">Aucune permission attribuée</p>';
    }
    
    // Construire le contenu du modal
    const contenu = `
        <div class="space-y-6">
            <!-- En-tête avec nom et priorité -->
            <div class="flex items-center justify-between pb-4 border-b border-white/20">
                <div>
                    <h4 class="text-2xl font-bold text-white mb-2">${role.nom}</h4>
                    <p class="text-white/70 text-sm">${role.description || 'Aucune description'}</p>
                </div>
                ${prioriteBadge}
            </div>
            
            <!-- Statistiques -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500/20 rounded-lg">
                            <i class="fas fa-users text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-white/60 text-xs">Membres</p>
                            <p class="text-white text-lg font-bold">${role.membres_count}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500/20 rounded-lg">
                            <i class="fas fa-shield-alt text-purple-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-white/60 text-xs">Permissions</p>
                            <p class="text-white text-lg font-bold">${role.permissions ? role.permissions.length : 0}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Permissions -->
            <div>
                <h5 class="text-white font-semibold mb-3 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-blue-400"></i>
                    Permissions attribuées
                </h5>
                ${permissionsHtml}
            </div>
            
            <!-- Dates -->
            <div class="pt-4 border-t border-white/20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-white/60">Créé le :</span>
                        <span class="text-white ml-2">${role.created_at}</span>
                    </div>
                    <div>
                        <span class="text-white/60">Modifié le :</span>
                        <span class="text-white ml-2">${role.updated_at}</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3 pt-4 border-t border-white/20">
                <button onclick="fermerModalDetails(); modifierRole(${role.id});" 
                        class="flex-1 px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-200 border border-blue-500/30">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </button>
                <button onclick="fermerModalDetails(); supprimerRole(${role.id}, '${role.nom}');" 
                        class="flex-1 px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-xl hover:bg-red-500/30 transition-all duration-200 border border-red-500/30"
                        ${role.membres_count > 0 ? 'disabled title="Impossible de supprimer un rôle avec des membres"' : ''}>
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('contenuDetails').innerHTML = contenu;
    document.getElementById('modalDetails').classList.remove('hidden');
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
    // Vérifier si c'est une modification (PUT) ou une création (POST)
    const method = this.dataset.method || this.method || 'POST';
    
    // Pour Laravel, on doit envoyer POST avec _method pour PUT
    if (method === 'PUT') {
        data._method = 'PUT';
    }
    
    fetch(url, {
        method: 'POST', // Toujours POST, Laravel gère _method
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
