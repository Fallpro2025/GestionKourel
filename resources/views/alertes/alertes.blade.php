<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }} - Gestion des Alertes</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                            500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b',
                        },
                        accent: {
                            rose: '#ec4899', orange: '#f97316', purple: '#8b5cf6', amber: '#f59e0b',
                        },
                        success: '#10b981', warning: '#f59e0b', error: '#ef4444', info: '#3b82f6',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { 
                            '0%': { opacity: '0', transform: 'translateY(30px) scale(0.95)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' }
                        },
                    },
                    boxShadow: {
                        'glass': '0 8px 32px rgba(0, 0, 0, 0.1)',
                        'glass-hover': '0 20px 40px rgba(0, 0, 0, 0.15)',
                    },
                    borderRadius: { 'xl': '1rem', '2xl': '1.5rem', '3xl': '2rem' },
                },
            },
        }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .metric-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .metric-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
            transform: translateY(-2px);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
        
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .alert-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .alert-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
            transform: translateY(-2px);
        }
        
        .alert-urgent {
            border-left: 4px solid #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(255,255,255,0.05));
        }
        
        .alert-warning {
            border-left: 4px solid #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(255,255,255,0.05));
        }
        
        .alert-info {
            border-left: 4px solid #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(255,255,255,0.05));
        }
        
        .alert-success {
            border-left: 4px solid #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(255,255,255,0.05));
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 min-h-screen">
    <div id="app">
        <!-- Sidebar Navigation -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white/10 backdrop-blur-xl border-r border-white/20">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-center p-6 border-b border-white/20">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-3">
                        <span class="text-white font-bold text-xl">DK</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Kourel</h1>
                        <p class="text-xs text-white/70">Alertes</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="/" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="/membres" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Membres
                    </a>
                    
                    <a href="/cotisations" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Cotisations
                    </a>
                    
                    <a href="/activites" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Activités
                    </a>
                    
                    <a href="/evenements" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Événements
                    </a>
                    
                    <a href="/alertes" class="nav-item flex items-center px-4 py-3 text-white rounded-xl bg-white/20 hover:bg-white/30 transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                        </svg>
                        Alertes
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="p-4 border-t border-white/20">
                    <div class="flex items-center p-3 bg-white/10 rounded-xl">
                        <div class="w-10 h-10 bg-gradient-to-r from-accent-rose to-accent-purple rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">AD</span>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Admin</p>
                            <p class="text-white/60 text-xs">Administrateur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="ml-64 min-h-screen">
            <!-- Top Header -->
            <header class="bg-white/10 backdrop-blur-xl border-b border-white/20 sticky top-0 z-40">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Gestion des Alertes</h2>
                            <p class="text-white/70">Surveillez et gérez les alertes automatiques</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <button class="btn-primary px-6 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Configurer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="p-6">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Alertes Actives</p>
                                <p class="text-3xl font-bold text-white mt-2">12</p>
                                <p class="text-red-400 text-sm mt-1">Urgentes</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">En Attente</p>
                                <p class="text-3xl font-bold text-white mt-2">8</p>
                                <p class="text-orange-400 text-sm mt-1">Avertissements</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Résolues</p>
                                <p class="text-3xl font-bold text-white mt-2">24</p>
                                <p class="text-green-400 text-sm mt-1">Cette semaine</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Taux Résolution</p>
                                <p class="text-3xl font-bold text-white mt-2">85%</p>
                                <p class="text-blue-400 text-sm mt-1">+5% ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alerts List -->
                <div class="space-y-4">
                    <!-- Alert 1 - Urgent -->
                    <div class="alert-card alert-urgent rounded-2xl p-6 card-hover">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="w-3 h-3 bg-red-500 rounded-full mt-2 animate-pulse"></div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-white">Retard de Paiement Critique</h3>
                                        <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded-full border border-red-500/30">
                                            URGENT
                                        </span>
                                    </div>
                                    <p class="text-white/70 mb-2">Fatou Diop - Cotisation Magal Touba 2025</p>
                                    <p class="text-white/60 text-sm">Montant: 50,000 FCFA • Retard: 15 jours</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-white/50 text-xs">Il y a 2 heures</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">SMS envoyé</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">WhatsApp envoyé</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-red-500/20 text-red-400 text-sm rounded-lg hover:bg-red-500/30 transition-all duration-300">
                                    Résoudre
                                </button>
                                <button class="px-3 py-1 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                    Voir
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert 2 - Warning -->
                    <div class="alert-card alert-warning rounded-2xl p-6 card-hover">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-white">Absence Non Justifiée</h3>
                                        <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full border border-orange-500/30">
                                            ATTENTION
                                        </span>
                                    </div>
                                    <p class="text-white/70 mb-2">Moustapha Fall - Répétition Chorale</p>
                                    <p class="text-white/60 text-sm">2ème absence consécutive • Action requise</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-white/50 text-xs">Il y a 4 heures</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">Email envoyé</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-orange-500/20 text-orange-400 text-sm rounded-lg hover:bg-orange-500/30 transition-all duration-300">
                                    Contacter
                                </button>
                                <button class="px-3 py-1 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                    Voir
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert 3 - Info -->
                    <div class="alert-card alert-info rounded-2xl p-6 card-hover">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-white">Nouvelle Activité Programmé</h3>
                                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full border border-blue-500/30">
                                            INFO
                                        </span>
                                    </div>
                                    <p class="text-white/70 mb-2">Répétition Chorale - Dimanche 15h</p>
                                    <p class="text-white/60 text-sm">Rappel automatique envoyé à tous les membres</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-white/50 text-xs">Il y a 6 heures</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">24 notifications envoyées</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-lg hover:bg-blue-500/30 transition-all duration-300">
                                    Voir Activité
                                </button>
                                <button class="px-3 py-1 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                    Ignorer
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert 4 - Success -->
                    <div class="alert-card alert-success rounded-2xl p-6 card-hover">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-white">Paiement Reçu</h3>
                                        <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                                            RÉSOLU
                                        </span>
                                    </div>
                                    <p class="text-white/70 mb-2">Aminata Sow - Cotisation Gamou</p>
                                    <p class="text-white/60 text-sm">Montant: 25,000 FCFA • Paiement confirmé</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-white/50 text-xs">Il y a 1 jour</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">Résolu automatiquement</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-lg hover:bg-green-500/30 transition-all duration-300">
                                    Voir Détails
                                </button>
                                <button class="px-3 py-1 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                    Archiver
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alert 5 - Warning -->
                    <div class="alert-card alert-warning rounded-2xl p-6 card-hover">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-bold text-white">Retard Excessif</h3>
                                        <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full border border-orange-500/30">
                                            ATTENTION
                                        </span>
                                    </div>
                                    <p class="text-white/70 mb-2">Ibrahima Kane - Goudi Aldiouma</p>
                                    <p class="text-white/60 text-sm">3ème retard consécutif • +15 minutes</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-white/50 text-xs">Il y a 1 jour</span>
                                        <span class="text-white/50 text-xs">•</span>
                                        <span class="text-white/50 text-xs">Avertissement envoyé</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-orange-500/20 text-orange-400 text-sm rounded-lg hover:bg-orange-500/30 transition-all duration-300">
                                    Contacter
                                </button>
                                <button class="px-3 py-1 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                    Voir Historique
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.alert-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            console.log('🚀 Page Alertes chargée avec succès !');
        });
    </script>
</body>
</html>
