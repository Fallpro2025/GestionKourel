@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Rôles - ' . $membre->nom . ' ' . $membre->prenom)

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('membres.index') }}" class="mr-4 text-white/60 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white"><i class="fas fa-user-tag mr-3"></i>Rôles de {{ $membre->nom }} {{ $membre->prenom }}</h1>
                        <p class="text-white/70 mt-1">Gestion des rôles et permissions</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="ouvrirModalAjoutRole()" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25"
                            id="btnAjouterRole">
                        <i class="fas fa-plus mr-2"></i>Ajouter un Rôle
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Informations du membre -->
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg p-6 mb-8 border border-white/20">
            <!-- DEBUG: Affichage des informations photo -->
            @if(config('app.debug'))
            <div class="mb-4 p-3 bg-yellow-500/20 border border-yellow-500/30 rounded-lg text-xs text-yellow-200">
                <strong>🔍 DEBUG Photo:</strong><br>
                - Photo brute (photo): <code>{{ $membre->photo ?? 'NULL' }}</code><br>
                - Photo URL (photo_url): <code>{{ $membre->photo_url ?? 'NULL' }}</code><br>
                - URL construite: <code>{{ $membre->photo_url ? asset('storage/' . $membre->photo_url) : 'AUCUNE' }}</code><br>
                - Photo existe: <code>{{ $membre->photo_url ? 'OUI' : 'NON' }}</code>
            </div>
            @endif
            
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    @if($membre->photo_url)
                    <img class="h-16 w-16 rounded-full object-cover" 
                         src="{{ asset('storage/' . $membre->photo_url) }}" 
                         alt="{{ $membre->nom }}"
                         onload="console.log('✅ Photo chargée:', this.src);" 
                         onerror="console.error('❌ Erreur photo:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center" style="display: none;">
                        <span class="text-white font-bold text-xl">{{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}</span>
                    </div>
                    @else
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ substr($membre->nom, 0, 1) }}{{ substr($membre->prenom, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-white">{{ $membre->nom }} {{ $membre->prenom }}</h2>
                    <p class="text-white/70">{{ $membre->email }}</p>
                    <p class="text-white/60 text-sm">Membre depuis {{ $membre->date_adhesion->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Liste des rôles -->
        <div class="bg-white/10 backdrop-blur-xl rounded-xl shadow-lg overflow-hidden border border-white/20">
            <div class="px-6 py-4 bg-white/5 border-b border-white/20">
                <h2 class="text-xl font-semibold text-white">Rôles Attribués</h2>
            </div>
            
            <div class="p-6">
                @if($membre->roles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($membre->roles as $role)
                    <div class="bg-white/5 rounded-lg p-4 border border-white/10 hover:bg-white/10 transition-colors duration-200" data-role-id="{{ $role->id }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-sm">{{ substr($role->nom, 0, 2) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $role->nom }}</h3>
                                    @if($role->pivot && $role->pivot->est_principal)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30" data-est-principal="true">
                                        <i class="fas fa-crown mr-1"></i>Principal
                                    </span>
                                    @else
                                    <span data-est-principal="false" class="hidden"></span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if(!$role->pivot || !$role->pivot->est_principal)
                                <button onclick="definirPrincipal({{ $membre->id }}, {{ $role->id }})" 
                                        class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                        title="Définir comme principal">
                                    <i class="fas fa-crown"></i>
                                </button>
                                @endif
                                <button onclick="modifierRole({{ $membre->id }}, {{ $role->id }})" 
                                        class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                        title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="supprimerRole({{ $membre->id }}, {{ $role->id }}, '{{ $role->nom }}')" 
                                        class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-sm text-white/70 mb-2">
                            {{ $role->description ?? 'Aucune description' }}
                        </div>
                        
                        <div class="text-xs text-white/60">
                            <div class="flex justify-between">
                                <span>Attribué le {{ $role->pivot->date_attribution ? \Carbon\Carbon::parse($role->pivot->date_attribution)->format('d/m/Y') : 'Date inconnue' }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    Niveau {{ $role->niveau_priorite }}
                                </span>
                            </div>
                            @if($role->pivot && $role->pivot->notes)
                            <div class="mt-2 p-2 bg-white/5 rounded text-xs" data-notes="{{ $role->pivot->notes }}">
                                <strong>Notes:</strong> {{ $role->pivot->notes }}
                            </div>
                            @else
                            <div data-notes="" class="hidden"></div>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <div class="text-xs text-white/60 mb-1">Permissions:</div>
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $permissions = is_array($role->permissions) ? $role->permissions : (is_string($role->permissions) ? json_decode($role->permissions, true) : []);
                                    $permissions = $permissions ?: [];
                                @endphp
                                @if(count($permissions) > 0)
                                    @foreach($permissions as $permission)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400 border border-green-500/30">
                                        {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                    </span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-white/40 italic">Aucune permission spécifique</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-white/60">
                    <i class="fas fa-user-tag text-4xl mb-4"></i>
                    <p class="text-lg">Aucun rôle attribué</p>
                    <p class="text-sm">Commencez par ajouter un rôle à ce membre</p>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajout de Rôle - Design Moderne -->
<div id="modalAjoutRole" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-[2200] transition-all duration-300">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <!-- Overlay avec animation -->
        <div class="fixed inset-0 bg-gradient-to-br from-blue-900/20 via-purple-900/20 to-pink-900/20 backdrop-blur-md"></div>
        
        <!-- Modal Container -->
        <div class="relative bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 w-full max-w-md transform transition-all duration-500 scale-95 opacity-0" id="modalContent">
            <!-- Header avec gradient -->
            <div class="relative p-6 pb-4">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 rounded-t-2xl"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-plus text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Ajouter un Rôle</h3>
                            <p class="text-sm text-gray-700">Attribuer un nouveau rôle au membre</p>
                        </div>
                    </div>
                    <button onclick="fermerModalAjoutRole()" 
                            class="h-8 w-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 group">
                        <i class="fas fa-times text-gray-500 group-hover:text-gray-700"></i>
                    </button>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="px-6 pb-6">
                <form id="formAjoutRole" class="space-y-6">
                    @csrf
                    
                    <!-- Sélection du rôle -->
                    <div class="space-y-2">
                        <label for="role_id" class="block text-sm font-semibold text-gray-800">Rôle à attribuer</label>
                        <div class="relative">
                            <select id="role_id" name="role_id" required 
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer text-gray-800">
                                <option value="" class="text-gray-800">Sélectionner un rôle</option>
                                @foreach($rolesDisponibles as $role)
                                <option value="{{ $role->id }}" class="text-gray-800">{{ $role->nom }} (Niveau {{ $role->niveau_priorite }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rôle principal -->
                    <div class="flex items-center space-x-3 p-4 bg-blue-50/80 rounded-xl border border-blue-200/80">
                        <input type="checkbox" id="est_principal" name="est_principal" 
                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-lg transition-all duration-200">
                        <div class="flex-1">
                            <label for="est_principal" class="text-sm font-semibold text-gray-800 cursor-pointer">
                                Définir comme rôle principal
                            </label>
                            <p class="text-xs text-gray-600 mt-1">Ce rôle sera marqué comme principal pour ce membre</p>
                        </div>
                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-crown text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-800">Notes (optionnel)</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none text-gray-800"
                                  placeholder="Ajoutez des notes sur l'attribution de ce rôle..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="fermerModalAjoutRole()" 
                                class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Ajouter le Rôle</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification de Rôle - Design Moderne -->
<div id="modalModifRole" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-[2200] transition-all duration-300">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <!-- Overlay avec animation -->
        <div class="fixed inset-0 bg-gradient-to-br from-purple-900/20 via-pink-900/20 to-red-900/20 backdrop-blur-md"></div>
        
        <!-- Modal Container -->
        <div class="relative bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 w-full max-w-md transform transition-all duration-500 scale-95 opacity-0" id="modalModifContent">
            <!-- Header avec gradient -->
            <div class="relative p-6 pb-4">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-red-500/10 rounded-t-2xl"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-edit text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Modifier le Rôle</h3>
                            <p class="text-sm text-gray-700">Modifier les paramètres du rôle</p>
                        </div>
                    </div>
                    <button onclick="fermerModalModifRole()" 
                            class="h-8 w-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 group">
                        <i class="fas fa-times text-gray-500 group-hover:text-gray-700"></i>
                    </button>
                </div>
            </div>
            
            <!-- Form Content -->
            <div class="px-6 pb-6">
                <form id="formModifRole" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sélection du rôle -->
                    <div class="space-y-2">
                        <label for="role_id_modif" class="block text-sm font-semibold text-gray-800">Rôle à modifier</label>
                        <div class="relative">
                            <select id="role_id_modif" name="role_id" 
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-gray-800 appearance-none cursor-pointer">
                                <option value="">Sélectionner un rôle...</option>
                                @foreach($rolesDisponibles as $role)
                                <option value="{{ $role->id }}" class="text-gray-800">{{ $role->nom }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rôle principal -->
                    <div class="flex items-center space-x-3 p-4 bg-purple-50/80 rounded-xl border border-purple-200/80">
                        <input type="checkbox" id="est_principal_modif" name="est_principal" 
                               class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded-lg transition-all duration-200">
                        <div class="flex-1">
                            <label for="est_principal_modif" class="text-sm font-semibold text-gray-800 cursor-pointer">
                                Définir comme rôle principal
                            </label>
                            <p class="text-xs text-gray-600 mt-1">Ce rôle sera marqué comme principal pour ce membre</p>
                        </div>
                        <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-crown text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes_modif" class="block text-sm font-semibold text-gray-800">Notes (optionnel)</label>
                        <textarea id="notes_modif" name="notes" rows="3"
                                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none text-gray-800"
                                  placeholder="Modifiez les notes sur l'attribution de ce rôle..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="fermerModalModifRole()" 
                                class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Mettre à jour</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Styles personnalisés pour les selects */
select {
    color: #1f2937 !important; /* text-gray-800 */
    background-color: white !important;
}

select option {
    color: #1f2937 !important; /* text-gray-800 */
    background-color: white !important;
}

select:focus {
    color: #1f2937 !important;
    background-color: white !important;
}

/* Amélioration du placeholder */
textarea::placeholder {
    color: #6b7280 !important; /* text-gray-500 */
}

input::placeholder {
    color: #6b7280 !important; /* text-gray-500 */
}
</style>
<script>
console.log('=== SCRIPT RÔLES CHARGÉ ===');
console.log('Membre actuel:', {{ $membre->id }});

let membreActuel = {{ $membre->id }};
let roleActuel = null;

// Test au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CHARGÉ ===');
    
    const btnAjouter = document.getElementById('btnAjouterRole');
    console.log('Bouton ajouter trouvé:', btnAjouter);
    
    const modal = document.getElementById('modalAjoutRole');
    console.log('Modal trouvé:', modal);
    
    const selectRole = document.getElementById('role_id');
    console.log('Select rôle trouvé:', selectRole);
    console.log('Nombre d\'options:', selectRole ? selectRole.options.length : 'N/A');
    
    if (btnAjouter && modal) {
        console.log('✅ Tout est prêt pour l\'ajout de rôle');
    } else {
        console.error('❌ Problème de chargement des éléments');
    }
    
    // Gestion de la fermeture par clic sur l'overlay
    document.getElementById('modalAjoutRole').addEventListener('click', function(e) {
        if (e.target === this) {
            fermerModalAjoutRole();
        }
    });
    
    document.getElementById('modalModifRole').addEventListener('click', function(e) {
        if (e.target === this) {
            fermerModalModifRole();
        }
    });
    
    // Gestion de la fermeture par Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('modalAjoutRole').classList.contains('hidden')) {
                fermerModalAjoutRole();
            }
            if (!document.getElementById('modalModifRole').classList.contains('hidden')) {
                fermerModalModifRole();
            }
        }
    });
});

// Ouvrir modal d'ajout de rôle avec animations
function ouvrirModalAjoutRole() {
    console.log('=== OUVRIR MODAL AJOUT RÔLE ===');
    console.log('Fonction appelée !');
    
    const modal = document.getElementById('modalAjoutRole');
    const modalContent = document.getElementById('modalContent');
    
    console.log('Modal trouvé:', modal);
    console.log('ModalContent trouvé:', modalContent);
    
    if (modal && modalContent) {
        console.log('✅ Éléments trouvés, ouverture du modal...');
        
        // Afficher le modal
        modal.classList.remove('hidden');
        
        // Animation d'entrée
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
        
        console.log('✅ Modal ouvert avec animations');
    } else {
        console.error('❌ Modal non trouvé !');
        console.error('Modal:', modal);
        console.error('ModalContent:', modalContent);
        
        // Test simple pour voir si le problème vient du modal
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.error('Test: Fonction appelée mais modal non trouvé');
        } else {
            console.error('Test: Fonction appelée mais modal non trouvé');
        }
    }
}

// Fermer modal d'ajout de rôle avec animations
function fermerModalAjoutRole() {
    const modal = document.getElementById('modalAjoutRole');
    const modalContent = document.getElementById('modalContent');
    
    if (modal && modalContent) {
        // Animation de sortie
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('formAjoutRole').reset();
        }, 300);
    }
}

// Ouvrir modal de modification avec animations
function modifierRole(membreId, roleId) {
    roleActuel = roleId;
    document.getElementById('formModifRole').action = `/membres/${membreId}/roles/${roleId}`;
    
    // Récupérer les données du rôle depuis le DOM
    const roleElement = document.querySelector(`[data-role-id="${roleId}"]`);
    if (roleElement) {
        const estPrincipal = roleElement.querySelector('[data-est-principal]')?.getAttribute('data-est-principal') === 'true';
        const notes = roleElement.querySelector('[data-notes]')?.getAttribute('data-notes') || '';
        
        // Pré-remplir le formulaire
        document.getElementById('role_id_modif').value = roleId;
        document.getElementById('est_principal_modif').checked = estPrincipal;
        document.getElementById('notes_modif').value = notes;
    }
    
    const modal = document.getElementById('modalModifRole');
    const modalContent = document.getElementById('modalModifContent');
    
    if (modal && modalContent) {
        // Afficher le modal
        modal.classList.remove('hidden');
        
        // Animation d'entrée
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
    }
}

// Fermer modal de modification avec animations
function fermerModalModifRole() {
    const modal = document.getElementById('modalModifRole');
    const modalContent = document.getElementById('modalModifContent');
    
    if (modal && modalContent) {
        // Animation de sortie
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            roleActuel = null;
        }, 300);
    }
}

// Supprimer un rôle
function supprimerRole(membreId, roleId, nomRole) {
    function executerSuppression() {
        fetch(`/membres/${membreId}/roles/${roleId}`, {
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
                console.log('Succès: ' + data.message);
            }
                location.reload();
            } else {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error(data.message);
            } else {
                console.error('Erreur: ' + data.message);
            }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error('Erreur lors de la suppression du rôle');
            } else {
                console.error('Erreur lors de la suppression du rôle');
            }
        });
    }
    
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.confirmation(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" de ce membre ?`, function(confirme) {
            if (confirme) {
                executerSuppression();
            }
        });
    } else {
        // Fallback vers confirm natif si alerteSystem n'est pas disponible
        if (confirm(`Êtes-vous sûr de vouloir supprimer le rôle "${nomRole}" de ce membre ?`)) {
            executerSuppression();
        }
    }
}

// Définir comme rôle principal
function definirPrincipal(membreId, roleId) {
    function executerAction() {
        fetch(`/membres/${membreId}/roles/${roleId}/principal`, {
            method: 'POST',
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
                console.log('Succès: ' + data.message);
            }
                location.reload();
            } else {
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error(data.message);
            } else {
                console.error('Erreur: ' + data.message);
            }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            if (typeof alerteSystem !== 'undefined' && alerteSystem) {
                alerteSystem.error('Erreur lors de la définition du rôle principal');
            } else {
                console.error('Erreur lors de la définition du rôle principal');
            }
        });
    }
    
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.confirmation('Êtes-vous sûr de vouloir définir ce rôle comme principal ?', function(confirme) {
            if (confirme) {
                executerAction();
            }
        });
    } else {
        // Fallback vers confirm natif si alerteSystem n'est pas disponible
        if (confirm('Êtes-vous sûr de vouloir définir ce rôle comme principal ?')) {
            executerAction();
        }
    }
}

// Gestion du formulaire d'ajout
document.getElementById('formAjoutRole').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('=== AJOUT RÔLE - CÔTÉ CLIENT ===');
    console.log('✅ Formulaire soumis !');
    
    const formData = new FormData(this);
    const data = {
        role_id: formData.get('role_id'),
        est_principal: formData.get('est_principal') === 'on',
        notes: formData.get('notes')
    };
    
    console.log('Données à envoyer:', data);
    console.log('URL:', `/membres/${membreActuel}/roles`);
    
    // Vérifier si les données sont valides
    if (!data.role_id) {
        console.error('❌ Aucun rôle sélectionné !');
    if (typeof alerteSystem !== 'undefined' && alerteSystem) {
        alerteSystem.warning('Veuillez sélectionner un rôle');
    } else {
        console.warn('Veuillez sélectionner un rôle');
    }
        return;
    }
    
    console.log('✅ Données valides, envoi en cours...');
    
    // Vérifier si alerteSystem existe
    if (typeof alerteSystem === 'undefined') {
        console.error('alerteSystem n\'est pas défini !');
        console.error('Erreur: Système d\'alertes non disponible');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('Token CSRF:', csrfToken);
    
    fetch(`/membres/${membreActuel}/roles`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('✅ Réponse reçue:', response.status, response.statusText);
        if (!response.ok) {
            console.error('❌ Erreur HTTP:', response.status, response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données de réponse:', data);
        if (data.success) {
            if (typeof alerteModerne !== 'undefined') {
                alerteModerne.succes(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            fermerModalAjoutRole();
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined') {
                alerteModerne.erreur(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('❌ Erreur fetch:', error);
        console.error('❌ Détails de l\'erreur:', error.message);
        if (typeof alerteSystem !== 'undefined') {
            alerteSystem.error('Erreur lors de l\'ajout du rôle: ' + error.message);
        } else {
            console.error('Erreur lors de l\'ajout du rôle: ' + error.message);
        }
    });
});

// Gestion du formulaire de modification
document.getElementById('formModifRole').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        est_principal: formData.get('est_principal') === 'on',
        notes: formData.get('notes')
    };
    
    fetch(this.action, {
        method: 'PUT',
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
                alerteModerne.succes(data.message);
            } else {
                alert('Succès: ' + data.message);
            }
            fermerModalModifRole();
            location.reload();
        } else {
            if (typeof alerteModerne !== 'undefined' && alerteModerne) {
                alerteModerne.erreur(data.message);
            } else {
                alert('Erreur: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof alerteSystem !== 'undefined' && alerteSystem) {
            alerteSystem.error('Erreur lors de la modification du rôle');
        } else {
            console.error('Erreur lors de la modification du rôle');
        }
    });
});
</script>
@endsection
