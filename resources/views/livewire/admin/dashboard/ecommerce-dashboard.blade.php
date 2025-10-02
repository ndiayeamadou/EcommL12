<div>
    <style>
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite;
        }
        
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .stat-counter {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .progress-bar {
            animation: progressFill 2s ease-out;
        }
        
        @keyframes progressFill {
            from { width: 0%; }
        }
    </style>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl flex items-center justify-center animate-pulse-slow">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Dashboard E-commerce</h1>
                <p class="text-slate-600">Vue d'ensemble de vos performances</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Produits</p>
                    <p class="text-2xl font-bold text-slate-900 stat-counter">{{ number_format($totalProducts) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +12% ce mois
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center animate-bounce-subtle">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Commandes</p>
                    <p class="text-2xl font-bold text-slate-900 stat-counter">{{ number_format($totalOrders) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +18% ce mois
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center animate-bounce-subtle">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Revenus Total</p>
                    <p class="text-2xl font-bold text-slate-900 stat-counter">{{ number_format($totalRevenue, 0, ',', ' ') }} F</p>
                    <p class="text-green-600 text-sm mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +25% ce mois
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center animate-bounce-subtle">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-l-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Clients</p>
                    <p class="text-2xl font-bold text-slate-900 stat-counter">{{ number_format($totalCustomers) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <span class="inline-flex items-centers">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +8% ce mois
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center animate-bounce-subtle">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-900">Revenus Mensuels</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition-colors">6M</button>
                    <button class="px-3 py-1 text-xs bg-slate-100 text-slate-600 rounded-full hover:bg-slate-200 transition-colors">1A</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Répartition par Catégorie</h3>
            <div class="space-y-4">
                @foreach($categoryStats as $index => $category)
                    @php
                        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500', 'bg-red-500', 'bg-cyan-500'];
                        $color = $colors[$index % count($colors)];
                        $maxProducts = $categoryStats->max('products_count');
                        $percentage = $maxProducts > 0 ? ($category->products_count / $maxProducts) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 {{ $color }} rounded-full"></div>
                                <span class="text-slate-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-slate-900 font-semibold">{{ $category->products_count }} produits</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="{{ $color }} h-2 rounded-full progress-bar" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Products Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-900">Produits les plus vendus</h3>
                <svg class="w-5 h-5 text-green-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div class="space-y-4">
                @foreach($topProducts as $index => $item)
                    @php
                        $gradients = [
                            'bg-gradient-to-r from-blue-400 to-blue-600',
                            'bg-gradient-to-r from-purple-400 to-purple-600',
                            'bg-gradient-to-r from-green-400 to-green-600',
                            'bg-gradient-to-r from-orange-400 to-orange-600',
                            'bg-gradient-to-r from-red-400 to-red-600'
                        ];
                        $gradient = $gradients[$index % count($gradients)];
                    @endphp
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        @if($item->product->primaryImage)
                            <img src="{{ $item->product->primaryImage->image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 {{ $gradient }} rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">{{ Str::limit($item->product->name, 25) }}</p>
                            <p class="text-sm text-slate-500">{{ number_format($item->total_sold) }} vendus</p>
                        </div>
                        <span class="text-green-600 text-sm font-semibold">+{{ rand(5, 25) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-900">Stock Faible</h3>
                <svg class="w-5 h-5 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="space-y-4">
                @foreach($outOfStockProducts as $product)
                    @php
                        $borderColor = $product->stock_quantity <= 2 ? 'border-red-200 hover:bg-red-50' : 
                                     ($product->stock_quantity <= 3 ? 'border-orange-200 hover:bg-orange-50' : 'border-yellow-200 hover:bg-yellow-50');
                        $bgColor = $product->stock_quantity <= 2 ? 'bg-gradient-to-r from-red-400 to-red-600' : 
                                 ($product->stock_quantity <= 3 ? 'bg-gradient-to-r from-orange-400 to-orange-600' : 'bg-gradient-to-r from-yellow-400 to-yellow-600');
                        $textColor = $product->stock_quantity <= 2 ? 'text-red-500' : 
                                   ($product->stock_quantity <= 3 ? 'text-orange-500' : 'text-yellow-500');
                    @endphp
                    <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors border {{ $borderColor }}">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 {{ $bgColor }} rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ $product->stock_quantity }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">{{ Str::limit($product->name, 20) }}</p>
                            <p class="text-sm {{ $textColor }}">
                                @if($product->stock_quantity <= 2)
                                    Seulement {{ $product->stock_quantity }} en stock
                                @elseif($product->stock_quantity <= 3)
                                    {{ $product->stock_quantity }} restants
                                @else
                                    Stock limite
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-900">Meilleurs Clients</h3>
                <svg class="w-5 h-5 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <div class="space-y-4">
                @foreach($topCustomers as $index => $customer)
                    @php
                        $gradients = [
                            'bg-gradient-to-r from-purple-400 to-pink-500',
                            'bg-gradient-to-r from-blue-400 to-cyan-500',
                            'bg-gradient-to-r from-green-400 to-teal-500',
                            'bg-gradient-to-r from-orange-400 to-red-500',
                            'bg-gradient-to-r from-indigo-400 to-purple-500'
                        ];
                        $badges = ['VIP', 'Gold', 'Gold', 'Silver', 'Bronze'];
                        $badgeColors = ['text-purple-600', 'text-blue-600', 'text-green-600', 'text-gray-600', 'text-orange-600'];
                        
                        $gradient = $gradients[$index % count($gradients)];
                        $badge = $badges[$index % count($badges)];
                        $badgeColor = $badgeColors[$index % count($badgeColors)];
                        $initials = strtoupper(substr($customer->user->firstname ?? 'U', 0, 1) . substr($customer->user->lastname ?? 'U', 0, 1));
                    @endphp
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-50 transition-colors">
                        <div class="w-12 h-12 {{ $gradient }} rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">{{ $initials }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">{{ $customer->user->firstname }} {{ $customer->user->lastname }}</p>
                            <p class="text-sm text-slate-500">{{ $customer->order_count }} commandes</p>
                        </div>
                        <span class="text-sm font-semibold {{ $badgeColor }}">{{ $badge }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-xl shadow-md p-6 card-hover mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-slate-900">Commandes Récentes</h3>
            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                Voir tout
                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Commande</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Client</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Produits</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Total</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Statut</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        @php
                            $totalAmount = $order->orderItems->sum(function($item) {
                                return $item->quantity * $item->price;
                            });
                            $statuses = ['Livré', 'En transit', 'Confirmé', 'Annulé', 'En cours'];
                            $statusColors = [
                                'Livré' => 'bg-green-100 text-green-800',
                                'En transit' => 'bg-yellow-100 text-yellow-800',
                                'Confirmé' => 'bg-blue-100 text-blue-800',
                                'Annulé' => 'bg-red-100 text-red-800',
                                'En cours' => 'bg-gray-100 text-gray-800'
                            ];
                            $randomStatus = $statuses[array_rand($statuses)];
                            $initials = strtoupper(substr($order->user->firstname ?? 'U', 0, 1) . substr($order->user->lastname ?? 'U', 0, 1));
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4">
                                <span class="font-medium text-slate-900">#{{ $order->tracking_no ?: 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ $initials }}</span>
                                    </div>
                                    <span class="text-slate-900">{{ $order->user->firstname }} {{ $order->user->lastname }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-slate-700">{{ $order->orderItems->count() }} article{{ $order->orderItems->count() > 1 ? 's' : '' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-semibold text-slate-900">{{ number_format($totalAmount, 0, ',', ' ') }} F</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-1 {{ $statusColors[$randomStatus] }} rounded-full text-xs font-medium">
                                    {{ $randomStatus }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-slate-500">{{ $order->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Conversion Rate -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm">Taux de conversion</p>
                    <p class="text-2xl font-bold">3.8%</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-float">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-blue-100 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                +0.5% ce mois
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-xl p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm">Panier moyen</p>
                    <p class="text-2xl font-bold">{{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 0, ',', ' ') }} F</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-float">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6 0a2 2 0 11-4 0m4 0a2 2 0 104 0"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-green-100 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                +12% ce mois
            </div>
        </div>

        <!-- Return Rate -->
        <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-orange-100 text-sm">Taux de retour</p>
                    <p class="text-2xl font-bold">2.1%</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-float">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-orange-100 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                -0.3% ce mois
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm">Satisfaction client</p>
                    <p class="text-2xl font-bold">4.8/5</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-float">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-purple-100 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                +0.2 ce mois
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Revenue Chart
            const ctx = document.getElementById('revenueChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($monthlyRevenue->pluck('month')->map(function($month) {
                            return \Carbon\Carbon::create()->month($month)->format('M');
                        })->reverse()->values()),
                        datasets: [{
                            label: 'Revenus',
                            data: @json($monthlyRevenue->pluck('revenue')->reverse()->values()),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgb(99, 102, 241)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Revenus: ' + context.parsed.y.toLocaleString() + ' F';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return (value / 1000000).toFixed(1) + 'M F';
                                    }
                                }
                            }
                        },
                        elements: {
                            point: {
                                hoverBackgroundColor: 'rgb(99, 102, 241)',
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }

            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.card-hover').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Add counter animation
            function animateCounters() {
                const counters = document.querySelectorAll('.stat-counter');
                counters.forEach(counter => {
                    const text = counter.textContent;
                    const target = parseInt(text.replace(/[^\d]/g, ''));
                    if (target > 0) {
                        const increment = target / 100;
                        let current = 0;
                        
                        const updateCounter = () => {
                            if (current < target) {
                                current += increment;
                                const formattedValue = Math.ceil(current);
                                if (text.includes('F')) {
                                    counter.textContent = formattedValue.toLocaleString() + ' F';
                                } else {
                                    counter.textContent = formattedValue.toLocaleString();
                                }
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = text; // Keep original formatting
                            }
                        };
                        
                        updateCounter();
                    }
                });
            }

            // Start counter animation
            setTimeout(animateCounters, 500);

            // Add hover effects for interactive elements
            document.querySelectorAll('button, .card-hover').forEach(element => {
                element.addEventListener('mouseenter', function() {
                    if (!this.style.transform.includes('translateY')) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                
                element.addEventListener('mouseleave', function() {
                    if (this.classList.contains('card-hover')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        });
    </script>
    @endpush
</div>