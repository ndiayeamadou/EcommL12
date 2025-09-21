<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 p-4 sm:p-6">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard des Commandes</h1>
                <p class="text-gray-600">Vue d'ensemble des performances commerciales</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <select 
                        wire:model.live="dateRange"
                        class="appearance-none px-4 py-2 pr-8 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">90 derniers jours</option>
                        <option value="365">1 an</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                
                <button 
                    wire:click="refreshData"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    wire:loading.attr="disabled">
                    <div wire:loading.remove>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualiser
                    </div>
                    <div wire:loading class="flex items-center">
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Chargement...
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total des commandes -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Commandes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                    @if($ordersGrowth != 0)
                        <div class="flex items-center mt-2">
                            @if($ordersGrowth > 0)
                                <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                                </svg>
                                <span class="text-sm text-green-600 font-medium">+{{ number_format(abs($ordersGrowth), 1) }}%</span>
                            @else
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                                </svg>
                                <span class="text-sm text-red-600 font-medium">{{ number_format($ordersGrowth, 1) }}%</span>
                            @endif
                            <span class="text-xs text-gray-500 ml-2">vs période précédente</span>
                        </div>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Chiffre d'Affaires</p>
                    <p class="text-2xl font-bold text-green-600">{{ $this->formattedTotalRevenue }}</p>
                    @if($revenueGrowth != 0)
                        <div class="flex items-center mt-2">
                            @if($revenueGrowth > 0)
                                <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                                </svg>
                                <span class="text-sm text-green-600 font-medium">+{{ number_format(abs($revenueGrowth), 1) }}%</span>
                            @else
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                                </svg>
                                <span class="text-sm text-red-600 font-medium">{{ number_format($revenueGrowth, 1) }}%</span>
                            @endif
                            <span class="text-xs text-gray-500 ml-2">vs période précédente</span>
                        </div>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panier moyen -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Panier Moyen</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $this->formattedAverageOrderValue }}</p>
                    <p class="text-xs text-gray-500 mt-2">Par commande</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients actifs -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Clients Actifs</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($totalCustomers) }}</p>
                    @if($customersGrowth != 0)
                        <div class="flex items-center mt-2">
                            @if($customersGrowth > 0)
                                <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                                </svg>
                                <span class="text-sm text-green-600 font-medium">+{{ number_format(abs($customersGrowth), 1) }}%</span>
                            @else
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                                </svg>
                                <span class="text-sm text-red-600 font-medium">{{ number_format($customersGrowth, 1) }}%</span>
                            @endif
                            <span class="text-xs text-gray-500 ml-2">vs période précédente</span>
                        </div>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section graphiques et analyses -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Graphique évolution -->
        <div class="xl:col-span-2 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Évolution des Ventes</h3>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button 
                        wire:click="setPeriod('daily')"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200 {{ $selectedPeriod === 'daily' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Jour
                    </button>
                    <button 
                        wire:click="setPeriod('weekly')"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200 {{ $selectedPeriod === 'weekly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Semaine
                    </button>
                    <button 
                        wire:click="setPeriod('monthly')"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-all duration-200 {{ $selectedPeriod === 'monthly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Mois
                    </button>
                </div>
            </div>
            
            <div class="h-80 flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-gray-100">
                <div class="text-center">
                    <div class="p-4 bg-blue-100 rounded-full inline-block mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-2">Graphique des revenus par {{ $selectedPeriod === 'daily' ? 'jour' : ($selectedPeriod === 'weekly' ? 'semaine' : 'mois') }}</p>
                    <p class="text-sm text-gray-500">{{ count($revenueData) }} point(s) de données disponibles</p>
                    <div class="mt-4 text-xs text-gray-400">
                        <p>Intégration Chart.js ou Recharts recommandée</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Répartition par Statut</h3>
            
            @if(count($ordersByStatus) > 0)
                <div class="space-y-4">
                    @foreach($ordersByStatus as $statusData)
                        @php
                            $percentage = $totalOrders > 0 ? ($statusData['count'] / $totalOrders) * 100 : 0;
                            $statusColors = [
                                'En cours de traitement' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-700'],
                                'Confirmé' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700'],
                                'En préparation' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-700'],
                                'Expédié' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700'],
                                'Livré' => ['bg' => 'bg-green-500', 'text' => 'text-green-700'],
                                'Terminé' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                                'Annulé' => ['bg' => 'bg-red-500', 'text' => 'text-red-700'],
                                'Remboursé' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-700'],
                            ];
                            $color = $statusColors[$statusData['status_message']] ?? ['bg' => 'bg-gray-400', 'text' => 'text-gray-700'];
                        @endphp
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-3 h-3 rounded-full {{ $color['bg'] }} flex-shrink-0"></div>
                                <span class="text-sm font-medium text-gray-900 truncate">{{ $statusData['status_message'] }}</span>
                            </div>
                            <div class="flex items-center space-x-3 flex-shrink-0">
                                <span class="text-sm {{ $color['text'] }} font-semibold">{{ $statusData['count'] }}</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="{{ $color['bg'] }} h-2 rounded-full transition-all duration-500 ease-out" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-10 text-right">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="p-3 bg-gray-100 rounded-full inline-block mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Aucune donnée de statut disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top produits et commandes récentes -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <!-- Top produits -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Top Produits
                </h3>
                <span class="text-sm text-gray-500">{{ count($topProducts) }} produits</span>
            </div>
            
            @if(count($topProducts) > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $index => $product)
                        <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <div class="flex items-center mt-1 space-x-4">
                                    <p class="text-xs text-gray-500">{{ number_format($product['total_quantity']) }} vendus</p>
                                    <p class="text-xs text-blue-600">{{ number_format($product['price'], 0, ',', ' ') }} F/unité</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-green-600">{{ number_format($product['total_revenue'], 0, ',', ' ') }} F</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="p-3 bg-gray-100 rounded-full inline-block mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Aucun produit vendu pour cette période</p>
                </div>
            @endif
        </div>

        <!-- Commandes récentes -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Commandes Récentes
                </h3>
                <span class="text-sm text-gray-500">{{ count($recentOrders) }} commandes</span>
            </div>
            
            @if(count($recentOrders) > 0)
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                        <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                #{{ $order['id'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $order['customer_name'] }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                        {{ $order['status'] === 'Terminé' ? 'bg-green-100 text-green-800' : 
                                           ($order['status'] === 'En cours de traitement' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($order['status'] === 'Annulé' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ $order['status'] }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $order['items_count'] }} article{{ $order['items_count'] > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-gray-900">{{ number_format($order['total'], 0, ',', ' ') }} F</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="p-3 bg-gray-100 rounded-full inline-block mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Aucune commande récente</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Section actions rapides -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Actions Rapides
            </h3>
            <div class="text-sm text-gray-500">Accès direct aux modules</div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/admin/orders" 
               class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <div class="p-2 bg-blue-500 rounded-lg mr-4 group-hover:bg-blue-600 transition-colors duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 group-hover:text-blue-900 transition-colors">Gérer les Commandes</p>
                    <p class="text-sm text-gray-600">Voir toutes les commandes</p>
                </div>
            </a>
            
            <a href="/admin/pos/sales" 
               class="group flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <div class="p-2 bg-green-500 rounded-lg mr-4 group-hover:bg-green-600 transition-colors duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 group-hover:text-green-900 transition-colors">Point de Vente</p>
                    <p class="text-sm text-gray-600">Nouvelle vente</p>
                </div>
            </a>
            
            <a href="/admin/products" 
               class="group flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <div class="p-2 bg-purple-500 rounded-lg mr-4 group-hover:bg-purple-600 transition-colors duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 group-hover:text-purple-900 transition-colors">Produits</p>
                    <p class="text-sm text-gray-600">Gérer l'inventaire</p>
                </div>
            </a>
            
            <a href="/admin/customers" 
               class="group flex items-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                <div class="p-2 bg-orange-500 rounded-lg mr-4 group-hover:bg-orange-600 transition-colors duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 group-hover:text-orange-900 transition-colors">Clients</p>
                    <p class="text-sm text-gray-600">Base de données clients</p>
                </div>
            </a>
        </div>
    </div>

    <!-- État de chargement global -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
            <div>
                <p class="text-lg font-medium text-gray-900">Chargement en cours...</p>
                <p class="text-sm text-gray-500">Actualisation des données</p>
            </div>
        </div>
    </div>


<!-- Styles personnalisés -->
<style>
    /* Animations fluides */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-in {
        animation: slideIn 0.4s ease-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.3s ease-out;
    }
    
    /* Effets de survol améliorés */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    /* Barres de progression animées */
    .progress-bar {
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Responsive amélioré */
    @media (max-width: 640px) {
        .grid-responsive {
            grid-template-columns: 1fr;
        }
        
        .text-responsive {
            font-size: 0.875rem;
        }
        
        .padding-responsive {
            padding: 1rem;
        }
    }
    
    /* États de focus améliorés */
    .focus-enhanced:focus {
        outline: none;
        ring: 2px solid #3b82f6;
        ring-offset: 2px;
        border-color: #3b82f6;
    }
    
    /* Animation des icônes */
    .icon-hover {
        transition: transform 0.2s ease-in-out;
    }
    
    .icon-hover:hover {
        transform: rotate(5deg) scale(1.1);
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Ombres personnalisées */
    .shadow-custom {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                    0 4px 6px -2px rgba(0, 0, 0, 0.05),
                    0 0 0 1px rgba(0, 0, 0, 0.05);
    }
    
    .shadow-custom-hover:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 1px rgba(0, 0, 0, 0.05);
    }
    
    /* Animation de pulsation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Boutons avec états */
    .btn-primary {
        @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg;
        @apply transition-all duration-200 transform hover:scale-105;
        @apply focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
        @apply shadow-lg hover:shadow-xl;
    }
    
    .btn-secondary {
        @apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg;
        @apply transition-all duration-200 transform hover:scale-105;
        @apply focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
    }
    
    /* Indicateurs de statut */
    .status-indicator {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        @apply transition-all duration-200;
    }
    
    .status-success {
        @apply bg-green-100 text-green-800 border border-green-200;
    }
    
    .status-warning {
        @apply bg-yellow-100 text-yellow-800 border border-yellow-200;
    }
    
    .status-error {
        @apply bg-red-100 text-red-800 border border-red-200;
    }
    
    .status-info {
        @apply bg-blue-100 text-blue-800 border border-blue-200;
    }
    
    /* Responsive breakpoints personnalisés */
    @media (min-width: 1600px) {
        .container-wide {
            max-width: 1400px;
        }
    }
</style>

</div>

<!-- Scripts JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des compteurs
        const animateCounters = () => {
            const counters = document.querySelectorAll('[data-counter]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-counter')) || 0;
                const duration = 2000; // 2 secondes
                const increment = target / (duration / 16); // 60 FPS
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };
                
                // Observer pour déclencher l'animation quand l'élément est visible
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                
                observer.observe(counter);
            });
        };
        
        // Animation des barres de progression
        const animateProgressBars = () => {
            const progressBars = document.querySelectorAll('.progress-bar');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const bar = entry.target;
                        const width = bar.getAttribute('data-width') || '0';
                        setTimeout(() => {
                            bar.style.width = width + '%';
                        }, 200);
                        observer.unobserve(bar);
                    }
                });
            }, { threshold: 0.5 });
            
            progressBars.forEach(bar => observer.observe(bar));
        };
        
        // Smooth scroll pour les liens internes
        const initSmoothScroll = () => {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        };
        
        // Gestion des états de focus pour l'accessibilité
        const initAccessibility = () => {
            // Ajout de classes pour les utilisateurs naviguant au clavier
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });
        };
        
        // Initialisation
        animateCounters();
        animateProgressBars();
        initSmoothScroll();
        initAccessibility();
        
        // Gestion des erreurs globales
        window.addEventListener('error', function(e) {
            console.error('Erreur Dashboard:', e.error);
            // Vous pouvez ajouter ici une notification d'erreur pour l'utilisateur
        });
        
        // Optimisation des performances
        const debounce = (func, wait) => {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        };
        
        // Gestionnaire de redimensionnement optimisé
        window.addEventListener('resize', debounce(() => {
            // Réajustement des éléments si nécessaire
            console.log('Fenêtre redimensionnée');
        }, 250));
    });
    
    // Fonction pour rafraîchir les données
    window.refreshDashboard = function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('refreshData');
        }
    };
    
    // Gestion des notifications
    window.addEventListener('notify', event => {
        const { text, type, status } = event.detail;
        // Implémentation de votre système de notifications
        console.log(`Notification ${type}: ${text}`);
    });
</script>