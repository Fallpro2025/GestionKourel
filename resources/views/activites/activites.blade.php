<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }} - Gestion des Activités</title>
    
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
                        <p class="text-xs text-white/70">Activités</p>
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
                    
                    <a href="/activites" class="nav-item flex items-center px-4 py-3 text-white rounded-xl bg-white/20 hover:bg-white/30 transition-all duration-300">
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
                    
                    <a href="/alertes" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
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
                            <h2 class="text-2xl font-bold text-white">Gestion des Activités</h2>
                            <p class="text-white/70">Planifiez et suivez vos activités</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <button class="btn-primary px-6 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Nouvelle Activité</span>
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
                                <p class="text-white/70 text-sm font-medium">Total Activités</p>
                                <p class="text-3xl font-bold text-white mt-2">18</p>
                                <p class="text-green-400 text-sm mt-1">Ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Présence Moy.</p>
                                <p class="text-3xl font-bold text-white mt-2">94%</p>
                                <p class="text-green-400 text-sm mt-1">+2% cette semaine</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Cette Semaine</p>
                                <p class="text-3xl font-bold text-white mt-2">3</p>
                                <p class="text-blue-400 text-sm mt-1">Activités</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-purple to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Retards</p>
                                <p class="text-3xl font-bold text-white mt-2">2</p>
                                <p class="text-orange-400 text-sm mt-1">Membres</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-orange to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activities Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Activity Card 1 -->
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-white">Répétition Chorale</h3>
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full border border-green-500/30">
                                Terminé
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-white/70">Dimanche 15 Septembre</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-white/70">15h00 - 17h00</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-white/70">Mosquée Centrale</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Présence</span>
                                <span class="text-green-400 font-bold">22/24 (92%)</span>
                            </div>
                            
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-6">
                            <button class="flex-1 px-4 py-2 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir Présences
                            </button>
                            <button class="flex-1 px-4 py-2 bg-primary-500/20 text-primary-400 text-sm rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Rapport
                            </button>
                        </div>
                    </div>
                    
                    <!-- Activity Card 2 -->
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-white">Goudi Aldiouma</h3>
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full border border-blue-500/30">
                                En cours
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-white/70">Dimanche 22 Septembre</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-white/70">14h00 - 18h00</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-white/70">Mosquée Centrale</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Présence</span>
                                <span class="text-blue-400 font-bold">En cours...</span>
                            </div>
                            
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-6">
                            <button class="flex-1 px-4 py-2 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                Marquer Présence
                            </button>
                            <button class="flex-1 px-4 py-2 bg-primary-500/20 text-primary-400 text-sm rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Activity Card 3 -->
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-white">Réunion de Planification</h3>
                            <span class="px-3 py-1 bg-purple-500/20 text-purple-400 text-xs rounded-full border border-purple-500/30">
                                Planifié
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-white/70">Vendredi 27 Septembre</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-white/70">19h00 - 21h00</span>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-white/70">Salle de Réunion</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-white/70">Participants</span>
                                <span class="text-purple-400 font-bold">Responsables</span>
                            </div>
                            
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-6">
                            <button class="flex-1 px-4 py-2 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir Détails
                            </button>
                            <button class="flex-1 px-4 py-2 bg-primary-500/20 text-primary-400 text-sm rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Add Activity Card -->
                    <div class="metric-card rounded-2xl p-6 card-hover border-2 border-dashed border-white/30 hover:border-primary-500/50 transition-all duration-300 cursor-pointer">
                        <div class="flex flex-col items-center justify-center h-full min-h-[200px] text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Nouvelle Activité</h3>
                            <p class="text-white/60 text-sm">Planifier une nouvelle activité</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.metric-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            console.log('🚀 Page Activités chargée avec succès !');
        });
    </script>
</body>
</html>
