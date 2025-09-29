<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion Kourel')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Script pour les alertes modernes -->
    <script src="{{ asset('js/alertes-modernes.js') }}"></script>
    
    <!-- Styles personnalisés pour les listes déroulantes -->
    <link href="{{ asset('css/select-styles.css') }}" rel="stylesheet">
    
    <!-- Styles personnalisés -->
    <style>
        /* Animations fluides */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Effet de survol pour les cartes */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradient animé */
        .gradient-animated {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">K</span>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-gray-900">Gestion Kourel</h1>
                            <p class="text-sm text-gray-500">Association Culturelle</p>
                        </div>
                    </div>
                    
                    <!-- Navigation principale -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                        <a href="{{ route('membres.liste-moderne') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('membres.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-users mr-2"></i>Membres
                        </a>
                        <a href="{{ route('cotisations') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('cotisations') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-money-bill-wave mr-2"></i>Cotisations
                        </a>
                        <a href="{{ route('activites') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('activites') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-calendar-alt mr-2"></i>Activités
                        </a>
                        <a href="{{ route('evenements') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('evenements') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-star mr-2"></i>Événements
                        </a>
                        <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('roles.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-user-tag mr-2"></i>Rôles
                        </a>
                    </div>
                </div>
                
                <!-- Menu utilisateur -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-gray-500 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                    
                    <!-- Profil utilisateur -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">AF</span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-gray-900">Alioune Fall</p>
                            <p class="text-xs text-gray-500">Administrateur</p>
                        </div>
                    </div>
                </div>
                
                <!-- Menu mobile -->
                <div class="md:hidden flex items-center">
                    <button id="menu-toggle" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-home mr-2"></i>Accueil
                </a>
                <a href="{{ route('membres.liste-moderne') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('membres.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-users mr-2"></i>Membres
                </a>
                <a href="{{ route('cotisations') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('cotisations') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-money-bill-wave mr-2"></i>Cotisations
                </a>
                <a href="{{ route('activites') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('activites') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-calendar-alt mr-2"></i>Activités
                </a>
                <a href="{{ route('evenements') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('evenements') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-star mr-2"></i>Événements
                </a>
                <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('roles.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-user-tag mr-2"></i>Rôles
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">K</span>
                        </div>
                        <span class="ml-2 text-xl font-bold">Gestion Kourel</span>
                    </div>
                    <p class="text-gray-300 text-sm">
                        Système de gestion moderne pour l'association culturelle Kourel. 
                        Gestion des membres, cotisations, activités et événements.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('membres.liste-moderne') }}" class="text-gray-300 hover:text-white transition-colors">Membres</a></li>
                        <li><a href="{{ route('cotisations') }}" class="text-gray-300 hover:text-white transition-colors">Cotisations</a></li>
                        <li><a href="{{ route('activites') }}" class="text-gray-300 hover:text-white transition-colors">Activités</a></li>
                        <li><a href="{{ route('evenements') }}" class="text-gray-300 hover:text-white transition-colors">Événements</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <div class="space-y-2 text-sm text-gray-300">
                        <p><i class="fas fa-envelope mr-2"></i>contact@kourel.org</p>
                        <p><i class="fas fa-phone mr-2"></i>+221 77 642 68 38</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Dakar, Sénégal</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Gestion Kourel. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script>
        // Menu mobile toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Fermer le menu mobile en cliquant à l'extérieur
        document.addEventListener('click', function(event) {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (!menuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Animation des cartes au survol
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Configuration CSRF pour les requêtes AJAX
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    
    @stack('scripts')
</body>
</html>