@extends('layouts.app-with-sidebar')

@section('title', 'Modifier le Membre - ' . $membre->nom . ' ' . $membre->prenom)

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-40 bg-white/10 backdrop-blur-xl border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('membres.show', $membre) }}" class="mr-4 text-white/60 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white"><i class="fas fa-edit mr-3"></i>Modifier le Membre</h1>
                        <p class="text-white/70 mt-1">{{ $membre->prenom }} {{ $membre->nom }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('membres.show', $membre) }}" 
                       class="px-4 py-2 bg-blue-500/20 text-blue-400 font-medium rounded-xl hover:bg-blue-500/30 transition-all duration-300 border border-blue-500/30">
                        <i class="fas fa-eye mr-2"></i>Voir les détails
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Messages d'alerte modernes -->
        @include('components.alertes-session')

        <!-- Formulaire -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-8">
            <form action="{{ route('membres.update', $membre) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Photo de profil -->
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                        @if($membre->photo_url)
                            <img src="{{ Storage::url($membre->photo_url) }}" 
                                 alt="{{ $membre->nom }}" 
                                 class="w-24 h-24 rounded-full object-cover">
                        @else
                            <span class="text-white font-bold text-2xl">
                                {{ strtoupper(substr($membre->prenom ?? 'M', 0, 1)) }}{{ strtoupper(substr($membre->nom ?? 'M', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <label for="photo" class="block text-white/80 text-sm font-medium mb-2">Photo de profil</label>
                        <input type="file" 
                               id="photo" 
                               name="photo" 
                               accept="image/*"
                               class="block w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30 transition-all duration-300">
                        <p class="text-white/60 text-xs mt-1">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                    </div>
                </div>
                
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
                                   value="{{ old('nom', $membre->nom) }}"
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
                                   value="{{ old('prenom', $membre->prenom) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Entrez le prénom"
                                   required>
                            @error('prenom')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-white/80 text-sm font-medium mb-2">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $membre->email) }}"
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
                                   value="{{ old('telephone', $membre->telephone) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="+221 XX XXX XX XX"
                                   required>
                            @error('telephone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-white/80 text-sm font-medium mb-2">Date de naissance</label>
                            <input type="date" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance', $membre->date_naissance?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            @error('date_naissance')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matricule -->
                        <div>
                            <label for="matricule" class="block text-white/80 text-sm font-medium mb-2">Matricule</label>
                            <input type="text" 
                                   id="matricule" 
                                   name="matricule" 
                                   value="{{ old('matricule', $membre->matricule) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Numéro de matricule">
                            @error('matricule')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="space-y-6 pt-6 border-t border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-3 text-green-400"></i>
                        Informations professionnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profession -->
                        <div>
                            <label for="profession" class="block text-white/80 text-sm font-medium mb-2">Profession</label>
                            <input type="text" 
                                   id="profession" 
                                   name="profession" 
                                   value="{{ old('profession', $membre->profession) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Votre profession">
                            @error('profession')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Niveau d'étude -->
                        <div>
                            <label for="niveau_etude" class="block text-white/80 text-sm font-medium mb-2">Niveau d'étude</label>
                            <select id="niveau_etude" 
                                    name="niveau_etude" 
                                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                                <option value="" style="background: #374151; color: white;">Sélectionnez un niveau</option>
                                <option value="Primaire" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Primaire' ? 'selected' : '' }}>Primaire</option>
                                <option value="Secondaire" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
                                <option value="Baccalauréat" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Baccalauréat' ? 'selected' : '' }}>Baccalauréat</option>
                                <option value="Licence" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option value="Master" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="Doctorat" style="background: #374151; color: white;" {{ old('niveau_etude', $membre->niveau_etude) == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                            </select>
                            @error('niveau_etude')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-white/80 text-sm font-medium mb-2">Adresse</label>
                            <textarea id="adresse" 
                                      name="adresse" 
                                      rows="3"
                                      class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                      placeholder="Votre adresse complète">{{ old('adresse', $membre->adresse) }}</textarea>
                            @error('adresse')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations du groupe -->
                <div class="space-y-6 pt-6 border-t border-white/20">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-users mr-3 text-purple-400"></i>
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
                                <option value="" style="background: #374151; color: white;">Sélectionnez un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" style="background: #374151; color: white;" {{ old('role_id', $membre->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
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
                                <option value="actif" style="background: #374151; color: white;" {{ old('statut', $membre->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" style="background: #374151; color: white;" {{ old('statut', $membre->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspendu" style="background: #374151; color: white;" {{ old('statut', $membre->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                            @error('statut')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'inscription -->
                        <div>
                            <label for="date_adhesion" class="block text-white/80 text-sm font-medium mb-2">Date d'inscription</label>
                            <input type="date" 
                                   id="date_adhesion" 
                                   name="date_adhesion" 
                                   value="{{ old('date_adhesion', $membre->date_adhesion->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            @error('date_adhesion')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
                    <a href="{{ route('membres.show', $membre) }}" 
                       class="px-6 py-3 text-white/70 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                        <i class="fas fa-save mr-2"></i>
                        Sauvegarder les modifications
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
    
    // Animation des inputs
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
