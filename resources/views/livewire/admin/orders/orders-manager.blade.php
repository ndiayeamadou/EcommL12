<!-- resources\views\livewire\admin\orders\orders-manager.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 p-4 sm:p-6">
    <!-- Header avec statistiques -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Gestion des Commandes
                </h1>
                <p class="text-gray-600">Suivez et gérez toutes vos commandes en temps réel</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- <button 
                    wire:click="exportOrders"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exporter
                </button> --}}
                
                <button 
                    onclick="window.location.reload()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Commandes</p>
                        {{-- <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p> --}}
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrdersToday) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Chiffre d'Affaires</p>
                        {{-- <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 0, ',', ' ') }} F</p> --}}
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenueToday, 0, ',', ' ') }} F</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">En Attente</p>
                        {{-- <p class="text-2xl font-bold text-orange-600">{{ $pendingOrders }}</p> --}}
                        <p class="text-2xl font-bold text-orange-600">{{ $pendingOrdersToday }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Terminées</p>
                        {{-- <p class="text-2xl font-bold text-emerald-600">{{ $completedOrders }}</p> --}}
                        <p class="text-2xl font-bold text-emerald-600">{{ $completedOrdersToday }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                        placeholder="Rechercher une commande...">
                </div>
            </div>

            <!-- Filtre par statut -->
            <div>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre par date -->
            <div>
                <select wire:model.live="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>
            </div>

            <!-- Filtre par mode de paiement -->
            <div>
                <select wire:model.live="paymentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Tous les paiements</option>
                    <option value="cash">Espèces</option>
                    <option value="card">Carte</option>
                    <option value="mobile">Mobile Money</option>
                    <option value="bank_transfer">Virement</option>
                </select>
            </div>

            <!-- Nombre par page -->
            <div>
                <select wire:model.live="perPage" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="10">10 par page</option>
                    <option value="15">15 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table des commandes -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('id')">
                            <div class="flex items-center space-x-1">
                                <span>N° Commande</span>
                                @if($sortField === 'id')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('fullname')">
                            <div class="flex items-center space-x-1">
                                <span>Client</span>
                                @if($sortField === 'fullname')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paiement
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                @if($sortField === 'created_at')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                    @if($order->tracking_no)
                                        <div class="text-xs text-gray-500">{{ $order->tracking_no }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($order->fullname, 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->fullname }}</div>
                                        @if($order->email)
                                            <div class="text-sm text-gray-500">{{ $order->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">
                                    {{ number_format($order->orderItems->sum(function($item) { 
                                        return $item->quantity * $item->price; 
                                    }), 0, ',', ' ') }} F
                                </div>
                                <div class="text-xs text-gray-500">{{ $order->orderItems->count() }} article(s)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'En cours de traitement' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'Confirmé' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'En préparation' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'Expédié' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'Livré' => 'bg-green-100 text-green-800 border-green-200',
                                        'Terminé' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'Annulé' => 'bg-red-100 text-red-800 border-red-200',
                                        'Remboursé' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$order->status_message] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    {{ $order->status_message }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $paymentColors = [
                                        'cash' => 'bg-green-100 text-green-800',
                                        'card' => 'bg-blue-100 text-blue-800',
                                        'mobile' => 'bg-purple-100 text-purple-800',
                                        'bank_transfer' => 'bg-indigo-100 text-indigo-800',
                                    ];
                                    $paymentLabels = [
                                        'cash' => 'Espèces',
                                        'card' => 'Carte',
                                        'mobile' => 'Mobile Money',
                                        'bank_transfer' => 'Virement',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$order->payment_mode] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button 
                                        wire:click="showOrderDetails({{ $order->id }})"
                                        class="cursor-pointer inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                        title="Voir détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    @can('manage_system_users')
                                    <button 
                                        wire:click="confirmDelete({{ $order->id }})"
                                        class="cursor-pointer inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                        title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="p-4 bg-gray-100 rounded-full">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                                        <p class="text-gray-500">Il n'y a pas encore de commandes correspondant à vos critères.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Modal détails de commande -->
    @if($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden animate-scale-in">
                <!-- Header du modal -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Détails de la Commande #{{ $selectedOrder->id }}</h3>
                            @if($selectedOrder->tracking_no)
                                {{-- <p class="text-sm text-gray-600 mt-1">N° de suivi: {{ $selectedOrder->tracking_no }}</p> --}}
                                <p class="text-sm text-gray-600 mt-1">N° de suivi: <a href="/admin/orders/{{ $selectedOrder->id }}/details" class="text-green-700 hover:text-yellow-500">{{ $selectedOrder->tracking_no }}</a></p>
                            @endif
                        </div>
                        <button 
                            wire:click="closeOrderModal"
                            class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all duration-200 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="p-6">
                        <!-- Informations client et commande -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Informations client -->
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Informations Client
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nom complet:</span>
                                        <span class="font-medium">{{ $selectedOrder->fullname }}</span>
                                    </div>
                                    @if($selectedOrder->email)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Email:</span>
                                            <span class="font-medium">{{ $selectedOrder->email }}</span>
                                        </div>
                                    @endif
                                    @if($selectedOrder->phone)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Téléphone:</span>
                                            <span class="font-medium">{{ $selectedOrder->phone }}</span>
                                        </div>
                                    @endif
                                    @if($selectedOrder->address)
                                        <div class="flex flex-col space-y-1">
                                            <span class="text-gray-600">Adresse:</span>
                                            <span class="font-medium text-sm">{{ $selectedOrder->address }}</span>
                                            @if($selectedOrder->city)
                                                <span class="text-sm text-gray-500">{{ $selectedOrder->city }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations commande -->
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Détails Commande
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date:</span>
                                        <span class="font-medium">{{ $selectedOrder->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Statut:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            {{ $selectedOrder->status_message }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Mode de paiement:</span>
                                        <span class="font-medium">
                                            @php
                                                $paymentLabels = [
                                                    'cash' => 'Espèces',
                                                    'card' => 'Carte',
                                                    'mobile' => 'Mobile Money',
                                                    'bank_transfer' => 'Virement',
                                                ];
                                            @endphp
                                            {{ $paymentLabels[$selectedOrder->payment_mode] ?? $selectedOrder->payment_mode }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Total:</span>
                                        <span class="text-green-600">
                                            {{ number_format($selectedOrder->orderItems->sum(function($item) { 
                                                return $item->quantity * $item->price; 
                                            }), 0, ',', ' ') }} F
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Articles commandés -->
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Articles Commandés ({{ $selectedOrder->orderItems->count() }})
                                </h4>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($selectedOrder->orderItems as $item)
                                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center space-x-4">
                                            <!-- Image du produit -->
                                            <div class="flex-shrink-0">
                                                @if($item->product && $item->product->images->count() > 0)
                                                    <img 
                                                        src="{{ $item->product->images->first()->image_url }}" 
                                                        alt="{{ $item->product->name }}"
                                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Détails du produit -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $item->product ? $item->product->name : 'Produit supprimé' }}
                                                        </h5>
                                                        @if($item->product)
                                                            <p class="text-xs text-gray-500 mt-1">SKU: {{ $item->product->sku }}</p>
                                                        @endif
                                                        @if($item->productColor)
                                                            <div class="flex items-center mt-1">
                                                                <span class="text-xs text-gray-500 mr-2">Couleur:</span>
                                                                <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $item->productColor->color->hex_code ?? '#gray' }}"></div>
                                                                <span class="text-xs text-gray-500 ml-1">{{ $item->productColor->color->name ?? 'N/A' }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ number_format($item->price, 0, ',', ' ') }} F</div>
                                                        <div class="text-xs text-gray-500">Qté: {{ number_format($item->quantity) }}</div>
                                                        <div class="text-sm font-bold text-green-600 mt-1">
                                                            {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mise à jour du statut -->
                        <div class="mt-6 bg-blue-50 rounded-xl p-4 border border-blue-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Mettre à jour le Statut
                            </h4>
                            
                            <!-- Alerte information sur la gestion du stock -->
                            @if($selectedOrder && $selectedOrder->status_message !== $newStatus)
                                <div class="mb-4 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <div class="text-sm text-yellow-800">
                                            @if($newStatus === 'Annulé')
                                                <strong>Attention :</strong> L'annulation de la commande retournera automatiquement les quantités au stock.
                                            @elseif($selectedOrder->status_message === 'Annulé' && $newStatus !== 'Annulé')
                                                <strong>Attention :</strong> La réactivation de la commande déduira à nouveau les quantités du stock.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau Statut</label>
                                        <select wire:model="newStatus" class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" 
                                                    @if($status === 'Annulé') class="text-red-600 font-medium" @endif>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Raison du changement (optionnel)</label>
                                        <input 
                                            type="text" 
                                            wire:model="statusUpdateReason"
                                            placeholder="Ex: Client a annulé la commande..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    </div>
                                </div>

                                <!-- Informations sur l'impact du changement -->
                                @if($selectedOrder && $selectedOrder->status_message !== $newStatus)
                                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-200">
                                        <div class="text-sm text-gray-600">
                                            <strong>Statut actuel :</strong> 
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                {{ $selectedOrder->status_message }}
                                            </span>
                                        </div>
                                        @if($newStatus === 'Annulé')
                                            <div class="text-sm text-green-600 mt-1">
                                                ✅ Les {{ $selectedOrder->orderItems->count() }} articles seront retournés au stock
                                            </div>
                                        @elseif($selectedOrder->status_message === 'Annulé' && $newStatus !== 'Annulé')
                                            <div class="text-sm text-orange-600 mt-1">
                                                📦 Les {{ $selectedOrder->orderItems->count() }} articles seront déduits du stock
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button 
                                        wire:click="updateOrderStatus"
                                        wire:loading.attr="disabled"
                                        wire:target="updateOrderStatus"
                                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg wire:loading wire:target="updateOrderStatus" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg wire:loading.remove wire:target="updateOrderStatus" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="updateOrderStatus">Mettre à jour</span>
                                        <span wire:loading wire:target="updateOrderStatus">Mise à jour...</span>
                                    </button>
                                    
                                    <button 
                                        wire:click="closeOrderModal"
                                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de confirmation de suppression -->
    @if($showDeleteModal && $orderToDelete)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500">
                            Êtes-vous sûr de vouloir supprimer la commande #{{ $orderToDelete->id }} ? 
                            Cette action est irréversible.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            wire:click="cancelDelete"
                            class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button 
                            wire:click="deleteOrder"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scale-in {
        from { 
            opacity: 0; 
            transform: scale(0.9); 
        }
        to { 
            opacity: 1; 
            transform: scale(1); 
        }
    }

    .animate-fade-in {
        animation: fade-in 0.2s ease-out;
    }

    .animate-scale-in {
        animation: scale-in 0.2s ease-out;
    }

    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
</style>

</div>