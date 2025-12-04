<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beauté Africaine Shop - Votre beauté, notre passion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fadeIn { animation: fadeIn 1s ease-out forwards; }
        .animate-slideInRight { animation: slideInRight 0.8s ease-out forwards; }
        .animate-pulse-soft { animation: pulse 2s ease-in-out infinite; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-lg shadow-sm z-50 animate-fadeIn">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold gradient-text">Beauté Africaine</h1>
                        <p class="text-xs text-gray-500">Votre beauté, notre passion</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="text-gray-700 hover:text-purple-600 transition-colors font-medium">Accueil</a>
                    <a href="#fonctionnalites" class="text-gray-700 hover:text-purple-600 transition-colors font-medium">Fonctionnalités</a>
                    <a href="#produits" class="text-gray-700 hover:text-purple-600 transition-colors font-medium">Produits</a>
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 transition-colors font-medium">Contact</a>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="/login" class="hidden sm:inline-block px-6 py-2 text-purple-600 hover:text-purple-700 font-medium transition-colors">
                        Connexion
                    </a>
                    <a href="/admin/dashboard" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6 animate-fadeInUp">
                    <div class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                        ✨ Solution E-commerce Complète
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                        Gérez votre boutique de
                        <span class="gradient-text">beauté</span> facilement
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed">
                        Une plateforme moderne pour gérer vos ventes de parfums, cosmétiques, vêtements et accessoires féminins au Sénégal.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="/admin/dashboard" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-center">
                            Commencer maintenant
                        </a>
                        <a href="#fonctionnalites" class="px-8 py-4 border-2 border-purple-600 text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all text-center">
                            Découvrir plus
                        </a>
                    </div>
                    
                    <!-- Stats rapides -->
                    <div class="grid grid-cols-3 gap-4 pt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">500+</div>
                            <div class="text-sm text-gray-600">Produits</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-pink-600">1000+</div>
                            <div class="text-sm text-gray-600">Clients</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-rose-600">24/7</div>
                            <div class="text-sm text-gray-600">Support</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Illustration -->
                <div class="relative animate-float">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 rounded-3xl opacity-20 blur-3xl"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8">
                        <svg class="w-full h-auto" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Shopping bag -->
                            <rect x="120" y="100" width="160" height="200" rx="20" fill="#F3E5F5" stroke="#9C27B0" stroke-width="3"/>
                            <path d="M140 100 C140 70, 260 70, 260 100" stroke="#9C27B0" stroke-width="3" fill="none"/>
                            <circle cx="200" cy="150" r="40" fill="#E1BEE7"/>
                            <path d="M200 130 L200 170 M180 150 L220 150" stroke="#9C27B0" stroke-width="3" stroke-linecap="round"/>
                            
                            <!-- Floating perfume bottle -->
                            <g class="animate-pulse-soft">
                                <rect x="280" y="80" width="60" height="80" rx="10" fill="#FCE4EC" stroke="#E91E63" stroke-width="2"/>
                                <rect x="295" y="60" width="30" height="25" rx="5" fill="#F8BBD0" stroke="#E91E63" stroke-width="2"/>
                                <circle cx="310" cy="120" r="15" fill="#FFF"/>
                            </g>
                            
                            <!-- Floating cosmetic -->
                            <g class="animate-pulse-soft" style="animation-delay: 0.5s">
                                <circle cx="80" cy="200" r="30" fill="#E1F5FE" stroke="#03A9F4" stroke-width="2"/>
                                <path d="M80 180 L80 220" stroke="#03A9F4" stroke-width="2"/>
                            </g>
                            
                            <!-- Stars -->
                            <path d="M50 80 L55 95 L70 95 L58 103 L63 118 L50 108 L37 118 L42 103 L30 95 L45 95 Z" fill="#FFD700"/>
                            <path d="M350 250 L353 258 L361 258 L355 263 L358 271 L350 265 L342 271 L345 263 L339 258 L347 258 Z" fill="#FFD700"/>
                            
                            <!-- Currency symbols -->
                            <text x="100" y="320" font-size="24" fill="#9C27B0" font-weight="bold">F CFA</text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fonctionnalites" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Fonctionnalités <span class="gradient-text">Puissantes</span>
                </h2>
                <p class="text-xl text-gray-600">Tout ce dont vous avez besoin pour gérer votre boutique</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="card-hover bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Statistiques en Temps Réel</h3>
                    <p class="text-gray-600 leading-relaxed">Suivez vos ventes, revenus et performances instantanément avec des tableaux de bord interactifs.</p>
                </div>
                
                <!-- Feature Card 2 -->
                <div class="card-hover bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8 border border-blue-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Gestion des Stocks</h3>
                    <p class="text-gray-600 leading-relaxed">Gérez facilement votre inventaire avec des alertes automatiques de réapprovisionnement.</p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="card-hover bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Paiements Sécurisés</h3>
                    <p class="text-gray-600 leading-relaxed">Acceptez les paiements en toute sécurité avec plusieurs options de paiement intégrées.</p>
                </div>
                
                <!-- Feature Card 4 -->
                <div class="card-hover bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-amber-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Gestion des Commandes</h3>
                    <p class="text-gray-600 leading-relaxed">Suivez et gérez toutes vos commandes depuis un seul endroit avec un système intuitif.</p>
                </div>
                
                <!-- Feature Card 5 -->
                <div class="card-hover bg-gradient-to-br from-pink-50 to-rose-50 rounded-2xl p-8 border border-pink-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-600 to-rose-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Gestion des Clients</h3>
                    <p class="text-gray-600 leading-relaxed">Maintenez des relations clients solides avec notre CRM intégré et historique d'achats.</p>
                </div>
                
                <!-- Feature Card 6 -->
                <div class="card-hover bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Rapports Détaillés</h3>
                    <p class="text-gray-600 leading-relaxed">Générez des rapports complets pour analyser vos performances et prendre de meilleures décisions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="produits" class="py-20 px-4 bg-gradient-to-br from-purple-100 via-pink-100 to-rose-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Nos <span class="gradient-text">Produits</span>
                </h2>
                <p class="text-xl text-gray-600">Découvrez notre large gamme de produits de beauté</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Product Category 1 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Parfums</h4>
                </div>
                
                <!-- Product Category 2 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Cosmétiques</h4>
                </div>
                
                <!-- Product Category 3 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-rose-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Vêtements</h4>
                </div>
                
                <!-- Product Category 4 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Pommades</h4>
                </div>
                
                <!-- Product Category 5 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Encens</h4>
                </div>
                
                <!-- Product Category 6 -->
                <div class="card-hover bg-white rounded-2xl p-6 text-center shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Accessoires</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Info Section -->
    <section id="contact" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">
                            Contactez <span class="gradient-text">Nous</span>
                        </h2>
                        <p class="text-xl text-gray-600">Nous sommes là pour vous aider</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-lg mb-1">Adresse</h4>
                                <p class="text-gray-600">Mbour, Marché Central<br>Thiès, Sénégal</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-lg mb-1">Téléphone</h4>
                                <p class="text-gray-600">00221 76 631 23 88</p>
                                <p class="text-sm text-gray-500 mt-1">Lun - Dim: 8h - 20h</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-lg mb-1">Email</h4>
                                <p class="text-gray-600">codesouest@gmail.com</p>
                                <p class="text-sm text-gray-500 mt-1">Réponse sous 24h</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4 pt-4">
                        <a href="#" class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center text-white hover:shadow-lg transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gradient-to-br from-pink-600 to-rose-600 rounded-xl flex items-center justify-center text-white hover:shadow-lg transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl flex items-center justify-center text-white hover:shadow-lg transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Security & Trust Section -->
                <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-3xl p-8 text-white">
                    <h3 class="text-3xl font-bold mb-6">Pourquoi nous choisir ?</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Sécurité Maximale</h4>
                                <p class="text-white/80">Vos données et transactions sont protégées avec les dernières technologies de sécurité.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Performance Rapide</h4>
                                <p class="text-white/80">Une interface ultra-rapide pour gérer votre boutique sans ralentissement.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Support 24/7</h4>
                                <p class="text-white/80">Notre équipe est disponible pour vous aider à tout moment.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">100% Responsive</h4>
                                <p class="text-white/80">Gérez votre boutique depuis n'importe quel appareil, partout.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-white/20">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-3xl font-bold mb-1">99.9%</div>
                                <div class="text-sm text-white/80">Uptime</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold mb-1">SSL</div>
                                <div class="text-sm text-white/80">Cryptage</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold mb-1">24/7</div>
                                <div class="text-sm text-white/80">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-4 gradient-bg">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Nos Chiffres</h2>
                <p class="text-xl text-white/80">Des résultats qui parlent d'eux-mêmes</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="stat-card rounded-2xl p-8 text-center text-white">
                    <div class="text-5xl font-bold mb-2">500+</div>
                    <div class="text-white/80">Produits en Stock</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center text-white">
                    <div class="text-5xl font-bold mb-2">1000+</div>
                    <div class="text-white/80">Clients Satisfaits</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center text-white">
                    <div class="text-5xl font-bold mb-2">5000+</div>
                    <div class="text-white/80">Commandes Livrées</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center text-white">
                    <div class="text-5xl font-bold mb-2">98%</div>
                    <div class="text-white/80">Taux de Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Prêt à développer votre <span class="gradient-text">boutique</span> ?
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Rejoignez des centaines de commerçants qui utilisent déjà notre plateforme
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin/dashboard" class="px-10 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-lg">
                    Accéder au Dashboard
                </a>
                <a href="tel:+221766312388" class="px-10 py-4 border-2 border-purple-600 text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition-all text-lg">
                    Nous Appeler
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Beauté Africaine</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Votre boutique de confiance pour tous vos produits de beauté et accessoires féminins.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-4">Navigation</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#accueil" class="hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="#fonctionnalites" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#produits" class="hover:text-white transition-colors">Produits</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-4">Produits</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Parfums</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cosmétiques</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Vêtements</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Accessoires</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>Mbour, Marché Central</li>
                        <li>Thiès, Sénégal</li>
                        <li>00221 76 631 23 88</li>
                        <li>codesouest@gmail.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">
                        © 2025 Beauté Africaine Shop. Tous droits réservés.
                    </p>
                    <div class="flex space-x-6 text-gray-400 text-sm">
                        <a href="#" class="hover:text-white transition-colors">Conditions d'utilisation</a>
                        <a href="#" class="hover:text-white transition-colors">Politique de confidentialité</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <button id="scrollTop" class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all opacity-0 pointer-events-none flex items-center justify-center z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-hover').forEach(el => observer.observe(el));

        // Scroll to top button
        const scrollTopBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.opacity = '1';
                scrollTopBtn.style.pointerEvents = 'auto';
            } else {
                scrollTopBtn.style.opacity = '0';
                scrollTopBtn.style.pointerEvents = 'none';
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>