<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
        <!-- Header moderne -->
        <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-white/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <span class="text-white font-bold text-xl">DK</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestion Kourel</h1>
                            <p class="text-sm text-gray-600">Plateforme moderne de gestion</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full"></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation moderne -->
        <nav class="bg-white/60 backdrop-blur-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8">
                    <button class="py-4 px-2 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        📊 Dashboard
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        👥 Membres
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        💰 Cotisations
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        📅 Activités
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        🎤 Événements
                    </button>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistiques en temps réel -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Membres</p>
                            <p class="text-3xl font-bold text-gray-900">4</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+12%</span>
                        <span class="text-gray-500 ml-2">vs mois dernier</span>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Cotisations</p>
                            <p class="text-3xl font-bold text-gray-900">500 000 FCFA</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+8%</span>
                        <span class="text-gray-500 ml-2">vs mois dernier</span>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Activités</p>
                            <p class="text-3xl font-bold text-gray-900">3</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-blue-600 font-medium">3 actives</span>
                        <span class="text-gray-500 ml-2">cette semaine</span>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Événements</p>
                            <p class="text-3xl font-bold text-gray-900">2</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75v10.5A2.25 2.25 0 004.5 19.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-orange-600 font-medium">2 à venir</span>
                        <span class="text-gray-500 ml-2">ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">📊 Dashboard Moderne</h2>
                
                <!-- Graphiques et analyses -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white">
                        <h3 class="text-xl font-bold mb-4">📈 Tendances des Présences</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span>Moyenne générale</span>
                                <span class="font-bold">86%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: 86%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-2xl p-6 text-white">
                        <h3 class="text-xl font-bold mb-4">💰 Recouvrement Cotisations</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span>Taux de recouvrement</span>
                                <span class="font-bold">95%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertes récentes -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">🚨 Alertes Récentes</h3>
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl border-l-4 bg-red-50 border-red-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">3 absences consécutives pour Diop Ibrahima</p>
                                    <p class="text-sm text-gray-600 mt-1">13 janvier 2025</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    haute
                                </span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl border-l-4 bg-yellow-50 border-yellow-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">Retard de paiement pour Sow Fatou</p>
                                    <p class="text-sm text-gray-600 mt-1">12 janvier 2025</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    moyenne
                                </span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl border-l-4 bg-blue-50 border-blue-500">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">Rappel: Magal de Touba dans 7 jours</p>
                                    <p class="text-sm text-gray-600 mt-1">13 janvier 2025</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    basse
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activités récentes -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">📅 Activités Récentes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-900">Répétition hebdomadaire</h4>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    active
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Dimanche à 15:00
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Mosquée centrale
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-900">Goudi Aldiouma</h4>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    active
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Vendredi à 19:00
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Maison du président
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-900">Prestation Gamou</h4>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    planifiée
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Samedi à 20:00
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Place publique
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        console.log('🚀 Gestion Kourel - Application chargée avec succès !');
        console.log('📊 Dashboard moderne avec interface glassmorphism');
        console.log('🎨 Design responsive et animations fluides');
    </script>
</body>
</html>
