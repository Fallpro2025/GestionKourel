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
                            @error('nom')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('prenom')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('telephone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('date_naissance')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div>
                            <label for="photo" class="block text-white/80 text-sm font-medium mb-2">
                                Photo de profil
                            </label>
                            <input type="file" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 transition-all duration-300">
                            @error('photo')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                        @error('adresse')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
                            @error('role_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('date_inscription')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            @error('statut')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-white/80 text-sm font-medium mb-2">
                            Notes supplémentaires
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4"
                                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                  placeholder="Informations supplémentaires sur le membre...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
// Animation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach((input, index) => {
        input.style.opacity = '0';
        input.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            input.style.transition = 'all 0.5s ease';
            input.style.opacity = '1';
            input.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
