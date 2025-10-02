<!-- resources\views\livewire\admin\dashboard\super-admin-dashboard.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Tableau de Bord Super Admin</h1>
                    <p class="text-purple-300 text-sm">Vue d'ensemble complète • {{ auth()->user()->firstname ?? 'Admin' }}</p>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="flex flex-wrap gap-2">
                @foreach(['today' => 'Aujourd\'hui', 'week' => 'Semaine', 'month' => 'Mois'] as $value => $label)
                    <button wire:click="changePeriod('{{ $value }}')" 
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ $selectedPeriod === $value ? 'bg-white text-purple-900 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stats Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $mainStats = [
                [
                    'title' => 'Chiffre d\'affaires',
                    'value' => number_format($stats['revenue'] ?? 0, 0, ',', ' '),
                    'suffix' => ' F',
                    'icon' => 'money',
                    'trend' => '+12%'
                ],
                [
                    'title' => 'Commandes', 
                    'value' => number_format($stats['orders'] ?? 0, 0, ',', ' '),
                    'suffix' => '',
                    'icon' => 'orders',
                    'trend' => '+8%'
                ],
                [
                    'title' => 'Clients actifs',
                    'value' => number_format($stats['customers'] ?? 0, 0, ',', ' '),
                    'suffix' => '',
                    'icon' => 'users',
                    'trend' => '+5%'
                ],
                [
                    'title' => 'Panier moyen',
                    'value' => number_format($stats['aov'] ?? 0, 0, ',', ' '),
                    'suffix' => ' F',
                    'icon' => 'cart',
                    'trend' => '+3%'
                ]
            ];
        @endphp

        @foreach($mainStats as $stat)
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 hover:border-white/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 rounded-lg bg-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($stat['icon'] === 'money')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            @elseif($stat['icon'] === 'orders')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            @elseif($stat['icon'] === 'users')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            @endif
                        </svg>
                    </div>
                    <span class="text-xs bg-green-500/20 text-green-300 px-2 py-1 rounded-full">{{ $stat['trend'] }}</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">{{ $stat['value'] }}{{ $stat['suffix'] }}</h3>
                <p class="text-sm text-purple-200">{{ $stat['title'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Deuxième ligne : Inventaire et Conversion -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Métriques de conversion -->
        <div class="lg:col-span-1 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h2 class="text-lg font-bold text-white mb-4">Performance Conversion</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-purple-200 mb-1">
                        <span>Visiteur → Client</span>
                        <span>{{ $conversionMetrics['visitor_to_customer'] ?? 0 }}%</span>
                    </div>
                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full transition-all duration-1000" 
                             style="width: {{ $conversionMetrics['visitor_to_customer'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-purple-200 mb-1">
                        <span>Panier → Commande</span>
                        <span>{{ $conversionMetrics['cart_to_order'] ?? 0 }}%</span>
                    </div>
                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full transition-all duration-1000" 
                             style="width: {{ $conversionMetrics['cart_to_order'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut Inventaire -->
        <div class="lg:col-span-2 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <h2 class="text-lg font-bold text-white mb-4">État de l'Inventaire</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 rounded-xl bg-white/5">
                    <div class="text-2xl font-bold text-white mb-1">{{ $stats['products'] ?? 0 }}</div>
                    <div class="text-sm text-purple-200">Total Produits</div>
                </div>
                <div class="text-center p-4 rounded-xl bg-red-500/20 border border-red-500/30">
                    <div class="text-2xl font-bold text-red-300 mb-1">{{ $stats['out_of_stock'] ?? 0 }}</div>
                    <div class="text-sm text-red-300">Rupture Stock</div>
                </div>
                <div class="text-center p-4 rounded-xl bg-orange-500/20 border border-orange-500/30">
                    <div class="text-2xl font-bold text-orange-300 mb-1">{{ $stats['low_stock'] ?? 0 }}</div>
                    <div class="text-sm text-orange-300">Stock Faible</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Troisième ligne : Meilleurs clients et produits -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <!-- Top Clients -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Meilleurs Clients</h2>
                <span class="text-xs bg-purple-500/20 text-purple-300 px-2 py-1 rounded-full">{{ count($topCustomers) }} clients</span>
            </div>
            <div class="space-y-3">
                @if(count($topCustomers) > 0)
                    @foreach($topCustomers as $index => $customer)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="text-white font-medium text-sm">{{ $customer['firstname'] }} {{ $customer['lastname'] }}</div>
                                    <div class="text-purple-300 text-xs">{{ $customer['email'] }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-emerald-400 font-bold text-sm">{{ number_format($customer['total_spent'] ?? 0, 0, ',', ' ') }} F</div>
                                <div class="text-purple-300 text-xs">{{ $customer['orders_count'] ?? 0 }} commandes</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-purple-300 text-center py-4">Aucun client trouvé</p>
                @endif
            </div>
        </div>

        <!-- Produits les plus vendus -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Produits Populaires</h2>
                <span class="text-xs bg-green-500/20 text-green-300 px-2 py-1 rounded-full">{{ count($bestSellingProducts) }} produits</span>
            </div>
            <div class="space-y-3">
                @if(count($bestSellingProducts) > 0)
                    @foreach($bestSellingProducts as $index => $product)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-white font-medium text-sm truncate">{{ $product['name'] }}</div>
                                    <div class="text-purple-300 text-xs">{{ $product['sku'] }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-emerald-400 font-bold text-sm">{{ number_format($product['revenue'] ?? 0, 0, ',', ' ') }} F</div>
                                <div class="text-purple-300 text-xs">{{ $product['units_sold'] ?? 0 }} unités</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-purple-300 text-center py-4">Aucun produit trouvé</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quatrième ligne : Alertes stock et CA par catégorie -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Produits en rupture de stock -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">⚠️ Rupture de Stock</h2>
                <span class="text-xs bg-red-500/20 text-red-300 px-2 py-1 rounded-full">{{ count($outOfStockProducts) }} produits</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @if(count($outOfStockProducts) > 0)
                    @foreach($outOfStockProducts as $product)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-red-500/10 border border-red-500/20">
                            <div class="flex-1 min-w-0">
                                <div class="text-white font-medium text-sm truncate">{{ $product['name'] }}</div>
                                <div class="text-red-300 text-xs">{{ $product['sku'] }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-red-300 font-bold text-sm">{{ number_format($product['price'] ?? 0, 0, ',', ' ') }} F</div>
                                <div class="text-red-400 text-xs">Stock: {{ $product['stock_quantity'] ?? 0 }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-green-300 text-center py-4">✅ Aucun produit en rupture</p>
                @endif
            </div>
        </div>

        <!-- Produits stock faible -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">📉 Stock Faible</h2>
                <span class="text-xs bg-orange-500/20 text-orange-300 px-2 py-1 rounded-full">{{ count($lowStockProducts) }} produits</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @if(count($lowStockProducts) > 0)
                    @foreach($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-orange-500/10 border border-orange-500/20">
                            <div class="flex-1 min-w-0">
                                <div class="text-white font-medium text-sm truncate">{{ $product['name'] }}</div>
                                <div class="text-orange-300 text-xs">{{ $product['sku'] }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-orange-300 font-bold text-sm">{{ number_format($product['price'] ?? 0, 0, ',', ' ') }} F</div>
                                <div class="text-orange-400 text-xs">Stock: {{ $product['stock_quantity'] ?? 0 }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-green-300 text-center py-4">✅ Stock optimal</p>
                @endif
            </div>
        </div>
    </div>
</div>