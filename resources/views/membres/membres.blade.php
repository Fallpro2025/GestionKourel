<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Kourel') }} - Gestion des Membres</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Gestion des vues en JavaScript vanilla -->
    <script>
        // Variable globale pour la vue actuelle
        let vueActuelle = localStorage.getItem('vueActuelle') || 'grille';
        
        // Fonction pour changer la vue directement
        function changerVueDirectement() {
            const vues = ['grille', 'liste', 'tableau'];
            const indexActuel = vues.indexOf(vueActuelle);
            const prochainIndex = (indexActuel + 1) % vues.length;
            vueActuelle = vues[prochainIndex];
            
            // Sauvegarder la vue dans localStorage
            localStorage.setItem('vueActuelle', vueActuelle);
            
            // Mettre à jour l'icône et le texte
            mettreAJourBoutonVue();
            
            // Appeler la fonction JavaScript existante
            if (vueActuelle === 'grille') {
                switchToGridView();
            } else if (vueActuelle === 'liste') {
                switchToListView();
            } else if (vueActuelle === 'tableau') {
                switchToTableView();
            }
        }
        
        // Fonction pour mettre à jour le bouton
        function mettreAJourBoutonVue() {
            const icone = document.getElementById('vueIcon');
            const texte = document.getElementById('vueTexte');
            
            const icones = {
                'grille': 'fas fa-th',
                'liste': 'fas fa-list',
                'tableau': 'fas fa-table'
            };
            
            const textes = {
                'grille': 'Grille',
                'liste': 'Liste',
                'tableau': 'Tableau'
            };
            
            if (icone) {
                icone.className = icones[vueActuelle] + ' w-5 h-5';
            }
            
            if (texte) {
                texte.textContent = textes[vueActuelle];
            }
        }
        
        // Initialiser la vue au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Mettre à jour le bouton avec la vue actuelle
            mettreAJourBoutonVue();
            
            // Appliquer la vue actuelle si ce n'est pas la grille par défaut
            if (vueActuelle !== 'grille') {
                if (vueActuelle === 'liste') {
                    switchToListView();
                } else if (vueActuelle === 'tableau') {
                    switchToTableView();
                }
            }
        });
    </script>
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
                        'slide-in-right': 'slideInRight 0.3s ease-out',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'loading': 'loading 1.5s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { 
                            '0%': { opacity: '0', transform: 'translateY(30px) scale(0.95)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' }
                        },
                        slideInRight: { 
                            '0%': { opacity: '0', transform: 'translateX(100%)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        loading: { 
                            '0%': { backgroundPosition: '200% 0' },
                            '100%': { backgroundPosition: '-200% 0' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)' },
                            '100%': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.8)' }
                        }
                    },
                    backdropBlur: { xs: '2px' },
                    boxShadow: {
                        'glass': '0 8px 32px rgba(0, 0, 0, 0.1)',
                        'glass-hover': '0 20px 40px rgba(0, 0, 0, 0.15)',
                        'neon': '0 0 20px rgba(59, 130, 246, 0.5)',
                        'neon-hover': '0 0 30px rgba(59, 130, 246, 0.8)',
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
        
        .glass-dark {
            background: rgba(31, 41, 55, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(75, 85, 99, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .hover-lift:hover { 
            transform: translateY(-2px); 
            transition: transform 0.2s ease; 
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #10b981, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .neon-border {
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        }
        
        .neon-border:hover {
            border: 1px solid rgba(59, 130, 246, 0.6);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
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
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .member-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .member-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.08));
            transform: translateY(-2px);
            border-color: rgba(59, 130, 246, 0.4);
        }
        
        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .status-badge:hover::before {
            left: 100%;
        }
        
        .search-input {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        
        /* Styles pour les options des select */
        .search-input option {
            background: #1f2937 !important;
            color: #ffffff !important;
            padding: 8px 12px;
        }
        
        .search-input option:hover {
            background: #374151 !important;
            color: #ffffff !important;
        }
        
        .search-input option:checked {
            background: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        .search-input option:focus {
            background: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        /* Style pour le select lui-même */
        select.search-input {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        select.search-input option {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        
        /* Forcer les styles sur tous les selects */
        select {
            color: #ffffff !important;
        }
        
        select option {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        
        select option:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }
        
        select option:checked {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        /* Styles pour les différentes vues */
        .view-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .view-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .view-table {
            display: table;
            width: 100%;
        }
        
        .member-list-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .member-list-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        
        .member-table-row {
            display: table-row;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .member-table-cell {
            display: table-cell;
            padding: 1rem;
            vertical-align: middle;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .member-table-cell:last-child {
            border-right: none;
        }
        
        /* Animation pour les modals */
        .modal-overlay {
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Styles pour les onglets */
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
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
        
        .btn-secondary {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 min-h-screen">
    <!-- Système de messages d'alerte -->
    <div id="alertContainer" class="fixed top-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div class="alert alert-success bg-green-500/20 border border-green-500/30 rounded-xl p-4 max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <span class="text-green-400 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error bg-red-500/20 border border-red-500/30 rounded-xl p-4 max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-400 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4 max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                    <span class="text-yellow-400 font-medium">{{ session('warning') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info bg-blue-500/20 border border-blue-500/30 rounded-xl p-4 max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                    <span class="text-blue-400 font-medium">{{ session('info') }}</span>
                </div>
            </div>
        @endif
    </div>
    <div id="app">
        <!-- Sidebar Navigation -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white/10 backdrop-blur-xl border-r border-white/20 sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-center p-6 border-b border-white/20">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-3 pulse-glow">
                        <span class="text-white font-bold text-xl">DK</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Gestion Kourel</h1>
                        <p class="text-xs text-white/70">Gestion des Membres</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="/" class="nav-item flex items-center px-4 py-3 text-white/80 rounded-xl hover:bg-white/20 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="/membres" class="nav-item flex items-center px-4 py-3 text-white rounded-xl bg-white/20 hover:bg-white/30 transition-all duration-300">
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
                            <h2 class="text-2xl font-bold text-white">Gestion des Membres</h2>
                            <p class="text-white/70">Gérez les membres de votre dahira/kourel</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Add Member Button -->
                            <button onclick="openAddMemberModal()" class="btn-primary px-6 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Ajouter Membre</span>
                            </button>
                            
                            <!-- Export Button -->
                            <button onclick="exportMembers()" class="btn-secondary px-4 py-2 text-white font-medium rounded-xl flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Exporter</span>
                            </button>
                            
                            <!-- View Options -->
                            <div class="relative">
                                <button onclick="changerVueDirectement()" class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl flex items-center space-x-2 hover:bg-white/20 transition-all duration-300">
                                    <i id="vueIcon" class="fas fa-th w-5 h-5"></i>
                                    <span id="vueTexte">Grille</span>
                                </button>
                            </div>
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
                                <p class="text-white/70 text-sm font-medium">Total Membres</p>
                                <p class="text-3xl font-bold text-white mt-2">24</p>
                                <p class="text-green-400 text-sm mt-1">+3 ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card rounded-2xl p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/70 text-sm font-medium">Membres Actifs</p>
                                <p class="text-3xl font-bold text-white mt-2">{{ $membres->where('statut', 'actif')->count() }}</p>
                                <p class="text-green-400 text-sm mt-1">
                                    @php
                                        $totalMembres = $membres->count();
                                        $membresActifs = $membres->where('statut', 'actif')->count();
                                        $pourcentageActifs = $totalMembres > 0 ? round(($membresActifs / $totalMembres) * 100) : 0;
                                    @endphp
                                    {{ $pourcentageActifs }}% actifs
                                </p>
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
                                <p class="text-white/70 text-sm font-medium">Nouveaux</p>
                                <p class="text-3xl font-bold text-white mt-2">3</p>
                                <p class="text-blue-400 text-sm mt-1">Ce mois</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-purple to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
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
                            <div class="w-12 h-12 bg-gradient-to-r from-accent-orange to-accent-rose rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filters -->
                <div class="metric-card rounded-2xl p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Rechercher par nom, téléphone, email..." class="search-input w-full px-4 py-3 pl-12 text-white placeholder-white/50 rounded-xl focus:outline-none">
                                <svg class="w-5 h-5 text-white/50 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="flex flex-wrap gap-3">
                            <select id="roleFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Tous les rôles</option>
                                <option value="membre" style="background: #1f2937; color: white;">Membre</option>
                                <option value="responsable" style="background: #1f2937; color: white;">Responsable</option>
                                <option value="animateur" style="background: #1f2937; color: white;">Animateur</option>
                                <option value="choriste" style="background: #1f2937; color: white;">Choriste</option>
                                <option value="trésorier" style="background: #1f2937; color: white;">Trésorier</option>
                                <option value="secrétaire" style="background: #1f2937; color: white;">Secrétaire</option>
                            </select>
                            
                            <select id="statusFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Tous les statuts</option>
                                <option value="actif" style="background: #1f2937; color: white;">Actif</option>
                                <option value="inactif" style="background: #1f2937; color: white;">Inactif</option>
                                <option value="suspendu" style="background: #1f2937; color: white;">Suspendu</option>
                                <option value="nouveau" style="background: #1f2937; color: white;">Nouveau</option>
                            </select>
                            
                            <select id="presenceFilter" class="search-input px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                                <option value="" style="background: #1f2937; color: white;">Toutes présences</option>
                                <option value="excellent" style="background: #1f2937; color: white;">Excellent (90%+)</option>
                                <option value="bon" style="background: #1f2937; color: white;">Bon (70-89%)</option>
                                <option value="moyen" style="background: #1f2937; color: white;">Moyen (50-69%)</option>
                                <option value="faible" style="background: #1f2937; color: white;">Faible (<50%)</option>
                            </select>
                            
                            <button onclick="clearFilters()" class="px-4 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-white/20">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Actifs: <span class="text-green-400 font-bold">{{ $membres->where('statut', 'actif')->count() }}</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Suspendus: <span class="text-orange-400 font-bold">{{ $membres->where('statut', 'suspendu')->count() }}</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Inactifs: <span class="text-red-400 font-bold">{{ $membres->where('statut', 'inactif')->count() }}</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-white/70 text-sm">Nouveaux ce mois: <span class="text-blue-400 font-bold">{{ $membres->where('created_at', '>=', now()->startOfMonth())->count() }}</span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Members Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="membersGrid">
                    @forelse($membres as $index => $membre)
                    <!-- Carte Membre {{ $index + 1 }} - Données Réelles -->
                    <div class="member-card rounded-2xl p-6 card-hover" 
                         data-name="{{ strtolower($membre->nom . ' ' . $membre->prenom) }}" 
                         data-role="{{ strtolower($membre->role ? $membre->role->nom : 'membre') }}" 
                         data-status="{{ $membre->statut }}" 
                         data-presence="{{ $membre->calculerTauxPresence() }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center overflow-hidden">
                                @if($membre->photo_url)
                                    <img src="{{ asset('storage/' . $membre->photo_url) }}" 
                                         alt="{{ $membre->prenom }} {{ $membre->nom }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    @php
                                        $initiales = 'M'; // Par défaut
                                        if ($membre->nom) {
                                            $nomParts = array_filter(explode(' ', $membre->nom));
                                            if (count($nomParts) >= 2) {
                                                $initiales = strtoupper($nomParts[0][0] . end($nomParts)[0]);
                                            } elseif (count($nomParts) === 1) {
                                                $initiales = strtoupper(substr($nomParts[0], 0, 2));
                                            }
                                        } elseif ($membre->prenom && $membre->nom_famille) {
                                            $initiales = strtoupper($membre->prenom[0] . $membre->nom_famille[0]);
                                        } elseif ($membre->prenom) {
                                            $initiales = strtoupper(substr($membre->prenom, 0, 2));
                                        }
                                    @endphp
                                    <span class="text-white font-bold text-lg">{{ $initiales }}</span>
                                @endif
                            </div>
                            <div class="status-badge px-3 py-1 
                                @if($membre->statut === 'actif') bg-green-500/20 text-green-400 border-green-500/30
                                @elseif($membre->statut === 'inactif') bg-red-500/20 text-red-400 border-red-500/30
                                @elseif($membre->statut === 'suspendu') bg-orange-500/20 text-orange-400 border-orange-500/30
                                @else bg-gray-500/20 text-gray-400 border-gray-500/30
                                @endif text-xs rounded-full border">
                                {{ ucfirst($membre->statut) }}
                            </div>
                        </div>
                        
                        <h3 class="text-white font-bold text-lg mb-1">{{ $membre->nom_complet }}</h3>
                        <p class="text-white/70 text-sm mb-2">{{ $membre->role ? $membre->role->nom : 'Membre' }}</p>
                        <p class="text-white/60 text-xs mb-2">{{ $membre->telephone ?? 'Non renseigné' }}</p>
                        <p class="text-white/60 text-xs mb-4">{{ $membre->email ?? 'Non renseigné' }}</p>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Présence</span>
                                <span class="text-green-400">{{ $membre->calculerTauxPresence() }}%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1">
                                <div class="bg-green-500 h-1 rounded-full" style="width: {{ $membre->calculerTauxPresence() }}%"></div>
                            </div>
                            
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Cotisations</span>
                                <span class="text-green-400">À jour</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 mt-4">
                            <button onclick="viewMemberDetails({{ $membre->id }})" class="flex-1 px-3 py-2 bg-white/10 text-white text-xs rounded-lg hover:bg-white/20 transition-all duration-300">
                                Voir
                            </button>
                            <button onclick="editMember({{ $membre->id }})" class="flex-1 px-3 py-2 bg-primary-500/20 text-primary-400 text-xs rounded-lg hover:bg-primary-500/30 transition-all duration-300">
                                Modifier
                            </button>
                        </div>
                    </div>
                    @empty
                    <!-- Message quand aucun membre -->
                    <div class="col-span-full text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold text-xl mb-2">Aucun membre trouvé</h3>
                        <p class="text-white/60 mb-6">Commencez par ajouter votre premier membre à l'association.</p>
                        <button onclick="openAddMemberModal()" class="px-6 py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                            Ajouter un membre
                        </button>
                    </div>
                    @endforelse
                    
                    <!-- Add Member Card -->
                    <div class="member-card rounded-2xl p-6 card-hover border-2 border-dashed border-white/30 hover:border-primary-500/50 transition-all duration-300 cursor-pointer" onclick="openAddMemberModal()">
                        <div class="flex flex-col items-center justify-center h-full min-h-[200px] text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Ajouter un Membre</h3>
                            <p class="text-white/60 text-sm">Cliquez pour ajouter un nouveau membre</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- Add Member Modal -->
        <div id="addMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="metric-card rounded-2xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-white">Ajouter un Membre</h3>
                        <button onclick="closeAddMemberModal()" class="text-white/60 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form id="addMemberForm" action="{{ route('membres.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Messages d'erreur -->
                        <div id="formErrors" class="hidden bg-red-500/20 border border-red-500/30 rounded-xl p-3 mb-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                <span class="text-red-400 font-medium text-sm">Erreurs de validation :</span>
                            </div>
                            <ul id="errorList" class="text-red-400 text-xs space-y-1">
                            </ul>
                        </div>
                        
                        <!-- Section 1: Informations de base -->
                        <div class="space-y-3">
                            <h4 class="text-white font-medium text-sm border-b border-white/20 pb-2">Informations de base</h4>
                            
                            <!-- Matricule (généré automatiquement) -->
                            <div>
                                <label class="block text-white/80 text-xs font-medium mb-1">Matricule</label>
                                <input type="text" 
                                       id="matriculeModal"
                                       readonly
                                       class="search-input w-full px-3 py-2 text-white/60 bg-white/5 rounded-lg text-sm cursor-not-allowed" 
                                       placeholder="MFTB-0001 (généré automatiquement)">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Nom <span class="text-red-400">*</span></label>
                                    <input type="text" 
                                           name="nom" 
                                           id="nomModal"
                                           required
                                           class="search-input w-full px-3 py-2 text-white placeholder-white/50 rounded-lg focus:outline-none text-sm" 
                                           placeholder="Nom">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Prénom <span class="text-red-400">*</span></label>
                                    <input type="text" 
                                           name="prenom" 
                                           id="prenomModal"
                                           required
                                           class="search-input w-full px-3 py-2 text-white placeholder-white/50 rounded-lg focus:outline-none text-sm" 
                                           placeholder="Prénom">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Téléphone <span class="text-red-400">*</span></label>
                                    <input type="tel" 
                                           name="telephone" 
                                           id="telephoneModal"
                                           required
                                           class="search-input w-full px-3 py-2 text-white placeholder-white/50 rounded-lg focus:outline-none text-sm" 
                                           placeholder="+221 77 123 45 67">
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           id="emailModal"
                                           class="search-input w-full px-3 py-2 text-white placeholder-white/50 rounded-lg focus:outline-none text-sm" 
                                           placeholder="email@example.com">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 2: Rôle et statut -->
                        <div class="space-y-3">
                            <h4 class="text-white font-medium text-sm border-b border-white/20 pb-2">Rôle et statut</h4>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Rôle <span class="text-red-400">*</span></label>
                                    <select name="role_id" 
                                            id="roleModal"
                                            required
                                            class="search-input w-full px-3 py-2 text-white rounded-lg focus:outline-none text-sm" 
                                            style="color: white; background: rgba(255, 255, 255, 0.1);">
                                        <option value="" style="background: #1f2937; color: white;">Sélectionner</option>
                                        @foreach(\App\Models\Role::all() as $role)
                                            <option value="{{ $role->id }}" style="background: #1f2937; color: white;">{{ $role->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-white/80 text-xs font-medium mb-1">Statut <span class="text-red-400">*</span></label>
                                    <select name="statut" 
                                            id="statutModal"
                                            required
                                            class="search-input w-full px-3 py-2 text-white rounded-lg focus:outline-none text-sm" 
                                            style="color: white; background: rgba(255, 255, 255, 0.1);">
                                        <option value="" style="background: #1f2937; color: white;">Sélectionner</option>
                                        <option value="actif" style="background: #1f2937; color: white;">Actif</option>
                                        <option value="inactif" style="background: #1f2937; color: white;">Inactif</option>
                                        <option value="suspendu" style="background: #1f2937; color: white;">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 3: Informations supplémentaires (optionnelles) -->
                        <div class="space-y-3">
                            <h4 class="text-white font-medium text-sm border-b border-white/20 pb-2">Informations supplémentaires</h4>
                            
                            <div>
                                <label class="block text-white/80 text-xs font-medium mb-1">Date de naissance</label>
                                <input type="date" 
                                       name="date_naissance" 
                                       id="dateNaissanceModal"
                                       class="search-input w-full px-3 py-2 text-white rounded-lg focus:outline-none text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-white/80 text-xs font-medium mb-1">Adresse</label>
                                <textarea name="adresse" 
                                          id="adresseModal"
                                          rows="2"
                                          class="search-input w-full px-3 py-2 text-white placeholder-white/50 rounded-lg focus:outline-none text-sm" 
                                          placeholder="Adresse complète"></textarea>
                            </div>
                            
                            <!-- Champ date d'inscription caché avec valeur par défaut -->
                            <input type="hidden" name="date_inscription" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <!-- Section 4: Photo (optionnelle) -->
                        <div class="space-y-3">
                            <h4 class="text-white font-medium text-sm border-b border-white/20 pb-2">Photo de profil</h4>
                            
                            <input type="file" 
                                   id="photoModal" 
                                   name="photo"
                                   accept="image/*"
                                   onchange="previewImageModal(this)"
                                   class="search-input w-full px-3 py-2 text-white rounded-lg focus:outline-none text-sm file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                            
                            <!-- Prévisualisation de l'image -->
                            <div id="imagePreviewModal" class="hidden">
                                <div class="relative inline-block">
                                    <img id="previewImgModal" src="" alt="Prévisualisation" class="w-16 h-16 object-cover rounded-lg border border-white/20">
                                    <button type="button" onclick="removeImageModal()" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="flex space-x-3 pt-4 border-t border-white/20">
                            <button type="button" onclick="closeAddMemberModal()" class="flex-1 px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-300 text-sm">
                                Annuler
                            </button>
                            <button type="submit" id="submitBtn" class="flex-1 px-4 py-2 btn-primary text-white rounded-lg text-sm">
                                <span id="submitText">Ajouter le membre</span>
                                <span id="submitSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    Ajout...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Détails Membre -->
    <div id="memberDetailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-xl border border-white/20 rounded-3xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto modal-content">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center overflow-hidden">
                            <img id="memberPhoto" src="" alt="Photo" class="w-full h-full object-cover hidden">
                            <span class="text-white font-bold text-2xl" id="memberInitials">FD</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white" id="memberName">Fatou Diop</h2>
                            <p class="text-white/70" id="memberRole">Choriste</p>
                        </div>
                    </div>
                    <button onclick="closeMemberDetails()" class="p-2 hover:bg-white/10 rounded-xl transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Onglets -->
                <div class="flex space-x-2 mb-6">
                    <button onclick="switchTab('info')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20 active">
                        Informations
                    </button>
                    <button onclick="switchTab('presence')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Présence
                    </button>
                    <button onclick="switchTab('cotisations')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Cotisations
                    </button>
                    <button onclick="switchTab('historique')" class="tab-button px-4 py-2 bg-white/10 text-white rounded-xl border border-white/20">
                        Historique
                    </button>
                </div>
                
                <!-- Contenu des onglets -->
                <div id="tab-info" class="tab-content active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Nom complet</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberFullName">Fatou Diop</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Téléphone</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberPhone">+221 77 123 45 67</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberEmail">fatou.diop@email.com</div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Rôle</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberRoleDetail">Choriste</div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Statut</label>
                                <div class="p-3 bg-white/5 rounded-xl">
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-full border border-green-500/30" id="memberStatus">Actif</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Date d'inscription</label>
                                <div class="p-3 bg-white/5 rounded-xl text-white" id="memberJoinDate">15 Janvier 2024</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-presence" class="tab-content">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-green-400 mb-1" id="modal-presence-rate">0%</div>
                                <div class="text-white/70 text-sm">Taux de présence</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-blue-400 mb-1" id="modal-sessions-present">0</div>
                                <div class="text-white/70 text-sm">Séances présentes</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-orange-400 mb-1" id="modal-delays">0</div>
                                <div class="text-white/70 text-sm">Retards</div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Dernières présences</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Répétition - 20 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Présent</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Goudi Aldiouma - 18 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full">Retard 5min</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Répétition - 15 Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Présent</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-cotisations" class="tab-content">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-green-400 mb-1">25,000 FCFA</div>
                                <div class="text-white/70 text-sm">Total payé</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-blue-400 mb-1">5,000 FCFA</div>
                                <div class="text-white/70 text-sm">Restant</div>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl">
                                <div class="text-2xl font-bold text-purple-400 mb-1">83%</div>
                                <div class="text-white/70 text-sm">Progression</div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Historique des paiements</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Janvier 2024</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Payé - 10,000 FCFA</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Décembre 2023</span>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Payé - 10,000 FCFA</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                                    <span class="text-white">Cotisation Novembre 2023</span>
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full">Partiel - 5,000 FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-historique" class="tab-content">
                    <div class="space-y-4">
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Inscription au groupe</span>
                                <span class="text-white/70 text-sm">15 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Fatou Diop a rejoint le groupe en tant que Choriste</p>
                        </div>
                        
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Première répétition</span>
                                <span class="text-white/70 text-sm">18 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Participation à la première répétition du groupe</p>
                        </div>
                        
                        <div class="p-4 bg-white/5 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Premier paiement</span>
                                <span class="text-white/70 text-sm">20 Janvier 2024</span>
                            </div>
                            <p class="text-white/70 text-sm">Paiement de la cotisation de janvier 2024</p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="mt-8 p-4 bg-white/5 rounded-xl">
                    <h3 class="text-lg font-semibold text-white mb-4">Actions rapides</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button onclick="changeMemberStatus('actif')" class="p-3 bg-green-500/20 text-green-400 rounded-xl hover:bg-green-500/30 transition-all duration-300 text-sm">
                            <i class="fas fa-check-circle mr-2"></i>Activer
                    </button>
                        <button onclick="changeMemberStatus('suspendu')" class="p-3 bg-orange-500/20 text-orange-400 rounded-xl hover:bg-orange-500/30 transition-all duration-300 text-sm">
                            <i class="fas fa-pause-circle mr-2"></i>Suspendre
                    </button>
                        <button onclick="changeMemberRole()" class="p-3 bg-blue-500/20 text-blue-400 rounded-xl hover:bg-blue-500/30 transition-all duration-300 text-sm">
                            <i class="fas fa-user-tag mr-2"></i>Changer rôle
                        </button>
                        <button onclick="sendMessageToMember()" class="p-3 bg-purple-500/20 text-purple-400 rounded-xl hover:bg-purple-500/30 transition-all duration-300 text-sm">
                            <i class="fas fa-envelope mr-2"></i>Envoyer SMS
                    </button>
                </div>
            </div>
                
                <!-- Actions principales -->
                <div class="flex justify-between items-center mt-6">
                    <div class="flex space-x-2">
                        <button onclick="exportMemberData()" class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300 text-sm">
                            <i class="fas fa-download mr-2"></i>Exporter
                        </button>
                        <button onclick="printMemberCard()" class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300 text-sm">
                            <i class="fas fa-print mr-2"></i>Imprimer
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="closeMemberDetails()" class="px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                            Fermer
                        </button>
                        <button onclick="editMemberFromDetails()" class="px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Membre -->
    <div id="editMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden modal-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-xl border border-white/20 rounded-3xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto modal-content">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Modifier le membre</h2>
                    <button onclick="closeEditMember()" class="p-2 hover:bg-white/10 rounded-xl transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Formulaire -->
                <form id="editMemberForm" class="space-y-6">
                    <!-- Photo de profil -->
                    <div class="p-4 bg-white/5 rounded-xl">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-camera mr-2"></i>Photo de profil
                        </h3>
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center overflow-hidden">
                                <img id="editMemberPhoto" src="" alt="Photo" class="w-full h-full object-cover hidden">
                                <span id="editMemberInitials" class="text-white font-bold text-2xl">M</span>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="editPhotoInput" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                                <button type="button" onclick="document.getElementById('editPhotoInput').click()" class="px-4 py-2 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition-all duration-300 text-sm">
                                    <i class="fas fa-upload mr-2"></i>Changer la photo
                                </button>
                                <p class="text-white/60 text-xs mt-2">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations personnelles -->
                    <div class="p-4 bg-white/5 rounded-xl">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>Informations personnelles
                        </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Prénom <span class="text-red-400">*</span></label>
                            <input type="text" id="editFirstName" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Prénom" required>
                                <div id="editFirstNameError" class="text-red-400 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Nom <span class="text-red-400">*</span></label>
                            <input type="text" id="editLastName" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Nom" required>
                                <div id="editLastNameError" class="text-red-400 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Téléphone <span class="text-red-400">*</span></label>
                            <input type="tel" id="editPhone" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="+221 77 123 45 67" required>
                                <div id="editPhoneError" class="text-red-400 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                            <input type="email" id="editEmail" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="email@example.com">
                                <div id="editEmailError" class="text-red-400 text-xs mt-1 hidden"></div>
                            </div>
                        </div>
                        </div>
                        
                    <!-- Rôle et statut -->
                    <div class="p-4 bg-white/5 rounded-xl">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-user-tag mr-2"></i>Rôle et statut
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Rôle <span class="text-red-400">*</span></label>
                                <select id="editRole" class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);" required>
                                <option value="" style="background: #1f2937; color: white;">Sélectionner un rôle</option>
                                    <option value="choriste" style="background: #1f2937; color: white;">Choriste</option>
                                    <option value="soliste" style="background: #1f2937; color: white;">Soliste</option>
                                    <option value="musicien" style="background: #1f2937; color: white;">Musicien</option>
                                    <option value="danseur" style="background: #1f2937; color: white;">Danseur</option>
                                    <option value="membre_actif" style="background: #1f2937; color: white;">Membre actif</option>
                                    <option value="membre_honoraire" style="background: #1f2937; color: white;">Membre honoraire</option>
                                <option value="responsable" style="background: #1f2937; color: white;">Responsable</option>
                                <option value="animateur" style="background: #1f2937; color: white;">Animateur</option>
                                    <option value="tresorier" style="background: #1f2937; color: white;">Trésorier</option>
                                    <option value="secretaire" style="background: #1f2937; color: white;">Secrétaire</option>
                            </select>
                                <div id="editRoleError" class="text-red-400 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Statut <span class="text-red-400">*</span></label>
                                <select id="editStatus" class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);" required>
                                <option value="actif" style="background: #1f2937; color: white;">Actif</option>
                                <option value="inactif" style="background: #1f2937; color: white;">Inactif</option>
                                <option value="suspendu" style="background: #1f2937; color: white;">Suspendu</option>
                                    <option value="ancien" style="background: #1f2937; color: white;">Ancien</option>
                            </select>
                                <div id="editStatusError" class="text-red-400 text-xs mt-1 hidden"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations supplémentaires -->
                    <div class="p-4 bg-white/5 rounded-xl">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informations supplémentaires
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Date d'inscription</label>
                                <input type="date" id="editJoinDate" class="search-input w-full px-4 py-3 text-white rounded-xl focus:outline-none" style="color: white; background: rgba(255, 255, 255, 0.1);">
                            </div>
                            
                            <div>
                                <label class="block text-white/80 text-sm font-medium mb-2">Notes</label>
                                <textarea id="editNotes" rows="3" class="search-input w-full px-4 py-3 text-white placeholder-white/50 rounded-xl focus:outline-none" placeholder="Notes sur le membre..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3 pt-6">
                        <button type="button" onclick="closeEditMember()" class="flex-1 px-6 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 transition-all duration-300">
                            Annuler
                        </button>
                        <button type="button" onclick="sauvegarderModifications()" class="flex-1 px-6 py-3 bg-primary-500 text-white rounded-xl hover:bg-primary-600 transition-all duration-300">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Système de Notifications Modernes -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Les notifications apparaîtront ici -->
    </div>
    
    <script>
        console.log('✅ JavaScript chargé correctement');
        console.log('🔍 Vérification des fonctions disponibles...');
        
        // Test de disponibilité des fonctions
                setTimeout(() => {
            console.log('🔍 Fonction editMember disponible:', typeof editMember);
            console.log('🔍 Fonction viewMemberDetails disponible:', typeof viewMemberDetails);
            console.log('🔍 Modal editMemberModal existe:', !!document.getElementById('editMemberModal'));
            console.log('🔍 Modal memberDetailsModal existe:', !!document.getElementById('memberDetailsModal'));
            }, 1000);
            
        // Test simple pour vérifier que le JavaScript fonctionne
        window.testEditButton = function() {
            console.log('🧪 TEST - Fonction testEditButton appelée');
            showNotification('Test: JavaScript fonctionne !', 'info');
            
            // Test d'ouverture du modal
            const modal = document.getElementById('editMemberModal');
            if (modal) {
                console.log('✅ Modal trouvé');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 2000);
            } else {
                console.error('❌ Modal non trouvé');
                showNotification('Erreur: Modal non trouvé', 'error');
            }
        };
        
        // Données des membres (récupérées depuis Laravel)
        const membresData = @json($membres ?? []);
        let membreActuel = null;
        
        console.log('📊 Données membres chargées:', membresData.length, 'membres');
        console.log('🔍 Premier membre (debug):', membresData[0]);
        
        // Fonction utilitaire pour calculer les initiales
        function calculerInitiales(membre) {
            let initiales = 'M'; // Par défaut
            
            if (membre.nom) {
                // Si on a un nom complet, prendre les premières lettres
                const nomParts = membre.nom.split(' ').filter(part => part.length > 0);
                if (nomParts.length >= 2) {
                    // Prendre la première lettre du prénom et du nom
                    initiales = (nomParts[0][0] + nomParts[nomParts.length - 1][0]).toUpperCase();
                } else if (nomParts.length === 1) {
                    // Si un seul mot, prendre les deux premières lettres
                    initiales = nomParts[0].substring(0, 2).toUpperCase();
                }
            } else if (membre.prenom && membre.nom_famille) {
                // Si on a prénom et nom séparés
                initiales = (membre.prenom[0] + membre.nom_famille[0]).toUpperCase();
            } else if (membre.prenom) {
                // Si on a seulement le prénom
                initiales = membre.prenom.substring(0, 2).toUpperCase();
            }
            
            return initiales;
        }
        
        // Système de Notifications Modernes
        function showNotification(message, type = 'success', duration = 4000) {
            const container = document.getElementById('notificationContainer');
            if (!container) return;
            
            // Créer l'élément de notification
            const notification = document.createElement('div');
            notification.className = `notification-item transform transition-all duration-500 ease-out translate-x-full opacity-0`;
            
            // Définir les styles selon le type
            let bgColor, iconColor, icon, borderColor;
            switch(type) {
                case 'success':
                    bgColor = 'bg-gradient-to-r from-green-500 to-green-600';
                    iconColor = 'text-green-100';
                    icon = 'fas fa-check-circle';
                    borderColor = 'border-green-400';
                    break;
                case 'error':
                    bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
                    iconColor = 'text-red-100';
                    icon = 'fas fa-exclamation-circle';
                    borderColor = 'border-red-400';
                    break;
                case 'warning':
                    bgColor = 'bg-gradient-to-r from-orange-500 to-orange-600';
                    iconColor = 'text-orange-100';
                    icon = 'fas fa-exclamation-triangle';
                    borderColor = 'border-orange-400';
                    break;
                case 'info':
                    bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
                    iconColor = 'text-blue-100';
                    icon = 'fas fa-info-circle';
                    borderColor = 'border-blue-400';
                    break;
                default:
                    bgColor = 'bg-gradient-to-r from-gray-500 to-gray-600';
                    iconColor = 'text-gray-100';
                    icon = 'fas fa-bell';
                    borderColor = 'border-gray-400';
            }
            
            notification.innerHTML = `
                <div class="flex items-center space-x-3 p-4 rounded-xl shadow-2xl backdrop-blur-sm border ${borderColor} ${bgColor} min-w-80 max-w-96">
                    <div class="flex-shrink-0">
                        <i class="${icon} ${iconColor} text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium text-sm leading-relaxed">${message}</p>
                    </div>
                    <button onclick="closeNotification(this)" class="flex-shrink-0 ml-2 text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            `;
            
            // Ajouter à la page
            container.appendChild(notification);
            
            // Animation d'entrée
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 100);
            
            // Auto-suppression
            setTimeout(() => {
                closeNotification(notification.querySelector('button'));
            }, duration);
        }
        
        function closeNotification(button) {
            const notification = button.closest('.notification-item');
            if (!notification) return;
            
            // Animation de sortie
            notification.classList.add('translate-x-full', 'opacity-0');
            
            // Supprimer après l'animation
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 500);
        }
        
        // Fonctions de base pour les modals
        function openAddMemberModal() {
            console.log('Ouverture modal ajout');
            const modal = document.getElementById('addMemberModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeAddMemberModal() {
            const modal = document.getElementById('addMemberModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        // Fonction pour voir les détails d'un membre
        function viewMemberDetails(memberId) {
            console.log('Voir détails membre:', memberId);
            
            // Trouver le membre dans les données
            const membre = membresData.find(m => m.id == memberId);
            if (!membre) {
                console.error('Membre non trouvé:', memberId);
                showNotification('Membre non trouvé', 'error');
                return;
            }
            
            membreActuel = membre;
            
            // Remplir le modal avec les vraies données
            remplirModalDetails(membre);
            
            const modal = document.getElementById('memberDetailsModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Fonction pour remplir le modal de détails
        function remplirModalDetails(membre) {
            // Photo de profil
            const photoElement = document.getElementById('memberPhoto');
            const elementInitiales = document.getElementById('memberInitials');
            
            // Utiliser photo_url au lieu de photo
            const photoUrl = membre.photo_url || membre.photo;
            
            if (photoUrl && photoUrl !== '') {
                // Construire l'URL complète de l'image
                const fullImageUrl = photoUrl.startsWith('http') ? photoUrl : `/storage/${photoUrl}`;
                
                // Afficher la photo si elle existe
                if (photoElement) {
                    photoElement.src = fullImageUrl;
                    photoElement.classList.remove('hidden');
                }
                if (elementInitiales) {
                    elementInitiales.classList.add('hidden');
                }
                console.log('🖼️ Photo détails chargée:', fullImageUrl);
            } else {
                // Afficher les initiales si pas de photo
                const initiales = calculerInitiales(membre);
                
                if (elementInitiales) {
                    elementInitiales.textContent = initiales;
                    elementInitiales.classList.remove('hidden');
                }
                if (photoElement) {
                    photoElement.classList.add('hidden');
                }
                console.log('👤 Initiales détails affichées:', initiales);
            }
            
            // Nom complet
            const nomComplet = membre.nom || 'Nom non défini';
            const elementsNom = ['memberName', 'memberFullName'];
            elementsNom.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = nomComplet;
            });
            
            // Rôle
            const role = membre.role || 'Rôle non défini';
            const elementsRole = ['memberRole', 'memberRoleDetail'];
            elementsRole.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = role;
            });
            
            // Téléphone
            const telephone = document.getElementById('memberPhone');
            if (telephone) telephone.textContent = membre.telephone || 'Non renseigné';
            
            // Email
            const email = document.getElementById('memberEmail');
            if (email) email.textContent = membre.email || 'Non renseigné';
            
            // Statut
            const statut = document.getElementById('memberStatus');
            if (statut) {
                statut.textContent = membre.statut || 'Non défini';
                // Appliquer la couleur selon le statut
                statut.className = 'px-3 py-1 text-sm rounded-full border';
                switch(String(membre.statut || '').toLowerCase()) {
                    case 'actif':
                        statut.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/30');
                        break;
                    case 'suspendu':
                        statut.classList.add('bg-orange-500/20', 'text-orange-400', 'border-orange-500/30');
                        break;
                    case 'inactif':
                        statut.classList.add('bg-red-500/20', 'text-red-400', 'border-red-500/30');
                        break;
                    default:
                        statut.classList.add('bg-gray-500/20', 'text-gray-400', 'border-gray-500/30');
                }
            }
            
            // Date d'inscription
            const dateInscription = document.getElementById('memberJoinDate');
            if (dateInscription) {
                if (membre.created_at) {
                    const date = new Date(membre.created_at);
                    dateInscription.textContent = date.toLocaleDateString('fr-FR', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
            } else {
                    dateInscription.textContent = 'Non renseignée';
                }
            }
            
            console.log('✅ Modal de détails rempli avec les données de:', membre.nom);
        }
        
        // Fonction de test pour diagnostiquer le problème
        function testEditMember(memberId) {
            console.log('🧪 TEST - Bouton Modifier cliqué');
            console.log('🧪 ID reçu:', memberId, 'Type:', typeof memberId);
            console.log('🧪 Données membres:', membresData);
            
            // Test simple d'abord
            showNotification('Test: Bouton Modifier cliqué pour le membre ID: ' + memberId, 'info');
            
            // Puis essayer d'ouvrir le modal
            const modal = document.getElementById('editMemberModal');
            if (modal) {
                console.log('✅ Modal trouvé, ouverture...');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('❌ Modal editMemberModal non trouvé');
                showNotification('Erreur: Modal de modification non trouvé', 'error');
            }
        }
        
        // Fonction pour modifier un membre - Version corrigée
        function editMember(memberId) {
            console.log('🔧 MODIFIER - Début fonction');
            console.log('🔧 ID reçu:', memberId, 'Type:', typeof memberId);
            
            try {
                // Vérifier que les données sont disponibles
                if (!membresData || membresData.length === 0) {
                    console.error('❌ Aucune donnée membre disponible');
                    showNotification('Erreur: Aucune donnée membre disponible', 'error');
                    return;
                }

                console.log('📊 Données membres:', membresData.length, 'membres');
                console.log('📋 IDs disponibles:', membresData.map(m => m.id));
                
                // Trouver le membre
                const membre = membresData.find(m => m.id == memberId);
                if (!membre) {
                    console.error('❌ Membre non trouvé avec ID:', memberId);
                    showNotification('Membre non trouvé avec l\'ID: ' + memberId, 'error');
                    return;
                }
                
                console.log('✅ Membre trouvé:', membre.nom);
                membreActuel = membre;
                
                // Ouvrir le modal directement
                const modal = document.getElementById('editMemberModal');
                if (!modal) {
                    console.error('❌ Modal editMemberModal non trouvé');
                    showNotification('Erreur: Modal de modification non trouvé', 'error');
                    return;
                }
                
                console.log('✅ Modal trouvé, ouverture...');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Remplir le formulaire si la fonction existe
                if (typeof remplirFormulaireModification === 'function') {
                    console.log('📝 Remplissage du formulaire...');
                    remplirFormulaireModification(membre);
                } else {
                    console.warn('⚠️ Fonction remplirFormulaireModification non disponible');
                }
                
                console.log('✅ Modal ouvert avec succès');
                
            } catch (error) {
                console.error('❌ Erreur dans editMember:', error);
                showNotification('Erreur lors de l\'ouverture du modal: ' + error.message, 'error');
            }
        }
        
        // Fonction pour remplir le formulaire de modification
        function remplirFormulaireModification(membre) {
            console.log('🔍 DEBUG - Données du membre:', membre);
            console.log('🔍 DEBUG - membre.nom:', membre.nom, 'Type:', typeof membre.nom);
            
            // Mettre à jour la photo de profil
            mettreAJourPhotoProfil(membre);
            
            // Séparer le nom en prénom et nom - Version simplifiée
            const nomComplet = membre.nom || '';
            console.log('🔍 DEBUG - Nom complet:', nomComplet);
            
            // Vérifier si les données ont déjà des champs séparés
            let prenom = '';
            let nom = '';
            
            // Si le membre a déjà des champs prénom et nom séparés
            if (membre.prenom && membre.nom_famille) {
                prenom = membre.prenom;
                nom = membre.nom_famille;
                console.log('🔍 DEBUG - Utilisation des champs séparés');
            } else if (membre.prenom) {
                prenom = membre.prenom;
                nom = membre.nom || '';
                console.log('🔍 DEBUG - Utilisation du champ prénom');
            } else {
                // Séparer le nom complet
                if (nomComplet.includes(' ')) {
                    const nomParts = nomComplet.split(' ');
                    prenom = nomParts[0] || '';
                    nom = nomParts.slice(1).join(' ') || '';
                } else {
                    // Si pas d'espace, considérer comme prénom
                    prenom = nomComplet;
                    nom = '';
                }
                console.log('🔍 DEBUG - Séparation du nom complet');
            }
            
            console.log('🔍 DEBUG - Prénom final:', prenom);
            console.log('🔍 DEBUG - Nom final:', nom);
            
            // Remplir les champs
            const prenomField = document.getElementById('editFirstName');
            if (prenomField) {
                prenomField.value = prenom;
                console.log('✅ Prénom rempli:', prenomField.value);
            }
            
            const nomField = document.getElementById('editLastName');
            if (nomField) {
                nomField.value = nom;
                console.log('✅ Nom rempli:', nomField.value);
            }
            
            const telephoneField = document.getElementById('editPhone');
            if (telephoneField) telephoneField.value = membre.telephone || '';
            
            const emailField = document.getElementById('editEmail');
            if (emailField) emailField.value = membre.email || '';
            
            const roleField = document.getElementById('editRole');
            if (roleField) {
                // Mapper le rôle correctement
                let roleValue = '';
                if (membre.role) {
                    // Si c'est un objet (relation Laravel), prendre le nom
                    if (typeof membre.role === 'object' && membre.role.nom) {
                        roleValue = membre.role.nom.toLowerCase();
                    } else if (typeof membre.role === 'string') {
                        roleValue = membre.role.toLowerCase();
                    }
                }
                
                // Mapper les valeurs pour correspondre aux options du select
                const roleMapping = {
                    'choriste': 'choriste',
                    'soliste': 'soliste',
                    'musicien': 'musicien',
                    'danseur': 'danseur',
                    'membre actif': 'membre_actif',
                    'membre honoraire': 'membre_honoraire',
                    'responsable': 'responsable',
                    'animateur': 'animateur',
                    'trésorier': 'tresorier',
                    'secrétaire': 'secretaire'
                };
                
                const mappedRole = roleMapping[roleValue] || roleValue;
                roleField.value = mappedRole;
                console.log('🔍 Rôle mappé:', roleValue, '→', mappedRole);
            }
            
            const statutField = document.getElementById('editStatus');
            if (statutField) statutField.value = String(membre.statut || 'actif').toLowerCase();
            
            const dateField = document.getElementById('editJoinDate');
            if (dateField && membre.created_at) {
                const date = new Date(membre.created_at);
                dateField.value = date.toISOString().split('T')[0];
            }
            
            const notesField = document.getElementById('editNotes');
            if (notesField) notesField.value = membre.notes || '';
            
            console.log('✅ Formulaire de modification rempli pour:', membre.nom);
        }
        
        // Fonction pour mettre à jour la photo de profil
        function mettreAJourPhotoProfil(membre) {
            const photoElement = document.getElementById('editMemberPhoto');
            const initialsElement = document.getElementById('editMemberInitials');
            
            // Utiliser photo_url au lieu de photo
            const photoUrl = membre.photo_url || membre.photo;
            
            if (photoUrl && photoUrl !== '') {
                // Construire l'URL complète de l'image
                const fullImageUrl = photoUrl.startsWith('http') ? photoUrl : `/storage/${photoUrl}`;
                
                // Afficher la photo si elle existe
                photoElement.src = fullImageUrl;
                photoElement.classList.remove('hidden');
                initialsElement.classList.add('hidden');
                console.log('🖼️ Photo chargée:', fullImageUrl);
            } else {
                // Afficher les initiales si pas de photo
                const initiales = calculerInitiales(membre);
                
                initialsElement.textContent = initiales;
                photoElement.classList.add('hidden');
                initialsElement.classList.remove('hidden');
                console.log('👤 Initiales affichées:', initiales);
            }
        }
        
        // Fonction pour prévisualiser la photo sélectionnée
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Vérifier la taille du fichier (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('Le fichier est trop volumineux. Taille maximum: 2MB', 'warning');
                    input.value = '';
                    return;
                }
                
                // Vérifier le type de fichier
                if (!file.type.startsWith('image/')) {
                    showNotification('Veuillez sélectionner un fichier image valide', 'warning');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoElement = document.getElementById('editMemberPhoto');
                    const initialsElement = document.getElementById('editMemberInitials');
                    
                    photoElement.src = e.target.result;
                    photoElement.classList.remove('hidden');
                    initialsElement.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Fonction simple pour fermer les modals
        function closeMemberDetails() {
            const modal = document.getElementById('memberDetailsModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        function closeEditMember() {
            const modal = document.getElementById('editMemberModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        // Fonction pour sauvegarder les modifications
        function sauvegarderModifications() {
            if (!membreActuel) {
                showNotification('Aucun membre sélectionné', 'warning');
                return;
            }
            
            // Récupérer les données du formulaire
            const prenom = document.getElementById('editFirstName').value.trim();
            const nom = document.getElementById('editLastName').value.trim();
            const telephone = document.getElementById('editPhone').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const role = document.getElementById('editRole').value;
            const statut = document.getElementById('editStatus').value;
            const dateInscription = document.getElementById('editJoinDate').value;
            const notes = document.getElementById('editNotes').value.trim();
            
            // Validation basique
            if (!prenom || !nom || !telephone) {
                showNotification('Veuillez remplir tous les champs obligatoires', 'warning');
                return;
            }
            
            // Mettre à jour les données locales
            membreActuel.nom = `${prenom} ${nom}`;
            membreActuel.telephone = telephone;
            membreActuel.email = email;
            membreActuel.role = role.charAt(0).toUpperCase() + role.slice(1);
            membreActuel.statut = statut.charAt(0).toUpperCase() + statut.slice(1);
            membreActuel.notes = notes;
            
            if (dateInscription) {
                membreActuel.created_at = new Date(dateInscription).toISOString();
            }
            
            console.log('✅ Membre modifié:', membreActuel);
            
            // Préparer les données pour l'envoi - Version corrigée selon la structure de la base
            const donneesModification = new FormData();
            donneesModification.append('_method', 'PUT'); // Méthode PUT pour Laravel
            donneesModification.append('prenom', prenom);
            donneesModification.append('nom', nom);
            donneesModification.append('telephone', telephone);
            donneesModification.append('email', email);
            donneesModification.append('statut', statut.toLowerCase()); // Convertir en minuscules
            donneesModification.append('date_adhesion', dateInscription ? new Date(dateInscription).toISOString().split('T')[0] : membreActuel.date_adhesion);
            donneesModification.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Ajouter l'image si elle a été sélectionnée
            const photoInput = document.getElementById('editPhotoInput');
            if (photoInput && photoInput.files && photoInput.files[0]) {
                donneesModification.append('photo', photoInput.files[0]);
                console.log('📷 Image ajoutée:', photoInput.files[0].name);
            }
            
            console.log('📤 Envoi des données (FormData):', donneesModification);
            
            // Envoyer les données au serveur
            fetch(`/membres/${membreActuel.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: donneesModification
            })
            .then(response => {
                console.log('📥 Réponse reçue:', response.status);
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
                    .then(data => {
                console.log('✅ Sauvegarde réussie:', data);
                
                // Mettre à jour les données locales
                membreActuel.nom = donneesModification.nom;
                membreActuel.telephone = donneesModification.telephone;
                membreActuel.email = donneesModification.email;
                membreActuel.role = donneesModification.role;
                membreActuel.statut = donneesModification.statut;
                membreActuel.notes = donneesModification.notes;
                membreActuel.created_at = donneesModification.created_at;
                
                // Fermer le modal
                closeEditMember();
                
                // Afficher un message de succès
                showNotification(`${membreActuel?.nom || 'Membre'} modifié avec succès en base de données !`, 'success');
                
                // Recharger la page pour voir les changements
                            setTimeout(() => {
                                window.location.reload();
                }, 1000);
                    })
                    .catch(error => {
                console.error('❌ Erreur lors de la sauvegarde:', error);
                showNotification(`Erreur lors de la sauvegarde: ${error.message}`, 'error');
            });
        }
        
        // Fonction pour changer d'onglet dans le modal de détails
        function switchTab(tabName) {
            console.log('Changement d\'onglet vers:', tabName);
            
            // Désactiver tous les onglets
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });
            
            // Masquer tout le contenu
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Activer l'onglet sélectionné
            const activeButton = document.querySelector(`[onclick="switchTab('${tabName}')"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
            
            // Afficher le contenu correspondant
            const activeContent = document.getElementById(`tab-${tabName}`);
            if (activeContent) {
                activeContent.classList.add('active');
            }
            
            // Remplir le contenu avec les données du membre actuel
            if (membreActuel) {
                remplirContenuOnglet(tabName, membreActuel);
            }
        }
        
        // Fonction pour remplir le contenu des onglets
        function remplirContenuOnglet(tabName, membre) {
            switch(tabName) {
                case 'presence':
                    remplirOngletPresence(membre);
                    break;
                case 'cotisations':
                    remplirOngletCotisations(membre);
                    break;
                case 'historique':
                    remplirOngletHistorique(membre);
                    break;
            }
        }
        
        // Remplir l'onglet Présence
        function remplirOngletPresence(membre) {
            // Statistiques de présence (simulées pour l'instant)
            const tauxPresence = Math.floor(Math.random() * 40) + 60; // 60-100%
            const seancesPresentes = Math.floor(Math.random() * 20) + 10; // 10-30
            const retards = Math.floor(Math.random() * 5); // 0-5
            
            const elements = {
                'modal-presence-rate': tauxPresence + '%',
                'modal-sessions-present': seancesPresentes,
                'modal-delays': retards
            };
            
            Object.keys(elements).forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = elements[id];
            });
        }
        
        // Remplir l'onglet Cotisations
        function remplirOngletCotisations(membre) {
            // Données de cotisations simulées
            const totalPaye = Math.floor(Math.random() * 50000) + 20000; // 20k-70k FCFA
            const restant = Math.floor(Math.random() * 10000); // 0-10k FCFA
            const progression = Math.floor((totalPaye / (totalPaye + restant)) * 100);
            
            console.log('Cotisations pour', membre.nom, ':', { totalPaye, restant, progression });
        }
        
        // Remplir l'onglet Historique
        function remplirOngletHistorique(membre) {
            console.log('Historique pour', membre.nom);
            // L'historique sera rempli avec les vraies données plus tard
        }
        
        // Actions rapides
        function changeMemberStatus(newStatus) {
            if (!membreActuel) return;
            
            const statusLabels = {
                'actif': 'Actif',
                'suspendu': 'Suspendu', 
                'inactif': 'Inactif',
                'ancien': 'Ancien'
            };
            
            if (confirm(`Changer le statut de ${membreActuel?.nom || 'Membre'} vers "${statusLabels[newStatus]}" ?`)) {
                membreActuel.statut = statusLabels[newStatus];
                
                // Mettre à jour l'affichage
                const statusElement = document.getElementById('memberStatus');
                if (statusElement) {
                    statusElement.textContent = statusLabels[newStatus];
                    statusElement.className = 'px-3 py-1 text-sm rounded-full border';
                    
                    switch(newStatus) {
                        case 'actif':
                            statusElement.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/30');
                    break;
                        case 'suspendu':
                            statusElement.classList.add('bg-orange-500/20', 'text-orange-400', 'border-orange-500/30');
                    break;
                        case 'inactif':
                            statusElement.classList.add('bg-red-500/20', 'text-red-400', 'border-red-500/30');
                    break;
                        case 'ancien':
                            statusElement.classList.add('bg-gray-500/20', 'text-gray-400', 'border-gray-500/30');
                    break;
            }
        }

                showNotification(`Statut de ${membreActuel?.nom || 'Membre'} changé vers "${statusLabels[newStatus]}"`, 'success');
            }
        }
        
        function changeMemberRole() {
            if (!membreActuel) return;
            
            const roles = ['Choriste', 'Soliste', 'Musicien', 'Danseur', 'Membre actif', 'Responsable'];
            const currentRole = membreActuel.role || 'Choriste';
            
            const roleInput = prompt(`Nouveau rôle pour ${membreActuel?.nom || 'Membre'}:\n\nRôles disponibles: ${roles.join(', ')}`, currentRole);
            
            if (roleInput && roleInput.trim() !== '' && roleInput !== currentRole) {
                membreActuel.role = roleInput.trim();
                
                // Mettre à jour l'affichage
                const elementsRole = ['memberRole', 'memberRoleDetail'];
                elementsRole.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = roleInput.trim();
                });
                
                showNotification(`Rôle de ${membreActuel?.nom || 'Membre'} changé vers "${roleInput.trim()}"`, 'success');
            }
        }
        
        function sendMessageToMember() {
            if (!membreActuel) return;
            
            const message = prompt(`Message à envoyer à ${membreActuel?.nom || 'Membre'} (${membreActuel?.telephone || 'N/A'}):`);
            
            if (message && message.trim() !== '') {
                showNotification(`SMS envoyé à ${membreActuel?.nom || 'Membre'}: "${message}"`, 'success');
            }
        }
        
        function exportMemberData() {
            if (!membreActuel) return;
            
            const data = {
                nom: membreActuel.nom,
                telephone: membreActuel.telephone,
                email: membreActuel.email,
                role: membreActuel.role,
                statut: membreActuel.statut,
                dateInscription: membreActuel.created_at
            };
            
            const dataStr = JSON.stringify(data, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `${(membreActuel?.nom || 'Membre').replace(/\s+/g, '_')}_donnees.json`;
            link.click();
            
            URL.revokeObjectURL(url);
            showNotification(`Données de ${membreActuel?.nom || 'Membre'} exportées`, 'success');
        }
        
        function printMemberCard() {
            if (!membreActuel) return;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Carte Membre - ${membreActuel.nom}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .card { border: 2px solid #333; padding: 20px; border-radius: 10px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .info { margin: 10px 0; }
                        .label { font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <div class="header">
                            <h1>Carte Membre</h1>
                            <h2>${membreActuel.nom}</h2>
                        </div>
                        <div class="info">
                            <span class="label">Téléphone:</span> ${membreActuel.telephone || 'Non renseigné'}
                        </div>
                        <div class="info">
                            <span class="label">Email:</span> ${membreActuel.email || 'Non renseigné'}
                        </div>
                        <div class="info">
                            <span class="label">Rôle:</span> ${membreActuel.role || 'Non défini'}
                        </div>
                        <div class="info">
                            <span class="label">Statut:</span> ${membreActuel.statut || 'Non défini'}
                        </div>
                        <div class="info">
                            <span class="label">Date d'inscription:</span> ${membreActuel.created_at ? new Date(membreActuel.created_at).toLocaleDateString('fr-FR') : 'Non renseignée'}
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
        
        function editMemberFromDetails() {
            if (membreActuel) {
                closeMemberDetails();
                editMember(membreActuel.id);
            }
        }
        
        // Fermer les modals en cliquant à l'extérieur
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
        
        console.log('✅ Toutes les fonctions de base chargées');
    </script>
</body>
</html>
