@extends('layouts.app-with-sidebar')

@section('title', 'Ajouter un Membre - Gestion Kourel')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('membres.index') }}" class="mr-4 text-white/60 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Ajouter un Membre</h1>
                        <p class="text-white/70 mt-1">Nouveau membre du groupe Kourel</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.index') }}" class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-list mr-2"></i>
                        Voir la liste
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages d'erreur -->
        @if($errors->any())
            <div class="mb-6 bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-400 font-medium">Erreurs de validation :</span>
                </div>
                <ul class="text-red-400 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/30 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <span class="text-green-400 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-400 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-8">
            <form action="{{ route('membres.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Informations personnelles -->
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-400"></i>
                        Informations personnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-white/80 text-sm font-medium mb-2">
                                Nom <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Entrez le nom"
                                   required>
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-white/80 text-sm font-medium mb-2">
                                Prénom <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Entrez le prénom"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-white/80 text-sm font-medium mb-2">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="exemple@email.com"
                                   required>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-white/80 text-sm font-medium mb-2">
                                Téléphone <span class="text-red-400">*</span>
                            </label>
                            <input type="tel" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="+221 XX XXX XX XX"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-white/80 text-sm font-medium mb-2">
                                Date de naissance <span class="text-red-400">*</span>
                            </label>
                            <input type="date" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   required>
                        </div>

                        <!-- Photo -->
                        <div>
                            <label for="photo" class="block text-white/80 text-sm font-medium mb-2">
                                Photo de profil
                            </label>
                            <div class="space-y-3">
                                <input type="file" 
                                       id="photo" 
                                       name="photo" 
                                       accept="image/*"
                                       onchange="previewImage(this)"
                                       class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 transition-all duration-300">
                                
                                <!-- Prévisualisation de l'image -->
                                <div id="imagePreview" class="hidden">
                                    <div class="relative inline-block">
                                        <img id="previewImg" src="" alt="Prévisualisation" class="w-32 h-32 object-cover rounded-xl border-2 border-white/20">
                                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-white/60 text-sm mt-2">Cliquez sur la croix pour supprimer l'image</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-white/80 text-sm font-medium mb-2">
                            Adresse <span class="text-red-400">*</span>
                        </label>
                        <textarea id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                  placeholder="Adresse complète du membre"
                                  required>{{ old('adresse') }}</textarea>
                    </div>
                </div>

                <!-- Informations du groupe -->
                <div class="space-y-6 pt-6 border-t border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-users mr-3 text-green-400"></i>
                        Informations du groupe
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Rôle -->
                        <div>
                            <label for="role_id" class="block text-white/80 text-sm font-medium mb-2">
                                Rôle dans le groupe <span class="text-red-400">*</span>
                            </label>
                            <select id="role_id" 
                                    name="role_id" 
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                    required>
                                <option value="">Sélectionnez un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date d'inscription -->
                        <div>
                            <label for="date_inscription" class="block text-white/80 text-sm font-medium mb-2">
                                Date d'inscription <span class="text-red-400">*</span>
                            </label>
                            <input type="date" 
                                   id="date_inscription" 
                                   name="date_inscription" 
                                   value="{{ old('date_inscription', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   required>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut" class="block text-white/80 text-sm font-medium mb-2">
                                Statut <span class="text-red-400">*</span>
                            </label>
                            <select id="statut" 
                                    name="statut" 
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                    required>
                                <option value="">Sélectionnez un statut</option>
                                <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
                    <a href="{{ route('membres.index') }}" 
                       class="px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer le membre
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('imagePreview');
    
    input.value = '';
    preview.classList.add('hidden');
}

// Validation côté client
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const photoInput = document.getElementById('photo');
    
    // Validation de la taille de l'image
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('La taille de l\'image ne doit pas dépasser 2MB');
                this.value = '';
                document.getElementById('imagePreview').classList.add('hidden');
                return false;
            }
            
            // Validation du type de fichier
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Seuls les formats JPEG, PNG, GIF et WebP sont autorisés');
                this.value = '';
                document.getElementById('imagePreview').classList.add('hidden');
                return false;
            }
        }
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const requiredFields = ['nom', 'prenom', 'email', 'telephone', 'date_naissance', 'adresse', 'role_id', 'date_inscription', 'statut'];
        let isValid = true;
        
        requiredFields.forEach(function(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires');
        }
    });
});
</script>
@endsection
