<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 p-4 sm:p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Clients</h1>
                <p class="text-gray-600">Gérez votre base de données clients efficacement</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button 
                    wire:click="exportCustomers"
                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exporter
                </button>
                
                <button 
                    wire:click="showCreateCustomer"
                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau Client
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Clients</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Clients Actifs</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($activeCustomers) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Nouveaux ce mois</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($newCustomersThisMonth) }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
                        placeholder="Rechercher un client...">
                </div>
            </div>

            <!-- Filtre par statut -->
            <div>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="suspended">Suspendus</option>
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

    <!-- Table des clients -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('id')">
                            <div class="flex items-center space-x-1">
                                <span>ID</span>
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('firstname')">
                            <div class="flex items-center space-x-1">
                                <span>Client</span>
                                @if($sortField === 'firstname')
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('email')">
                            <div class="flex items-center space-x-1">
                                <span>Email</span>
                                @if($sortField === 'email')
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
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200" wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <span>Date inscription</span>
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
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">#{{ $customer->id }}</div>
                                    @if($customer->customer_number)
                                        <div class="text-xs text-gray-500">{{ $customer->customer_number }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ $customer->initials() }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $customer->firstname }} {{ $customer->lastname }}
                                        </div>
                                        @if($customer->gender)
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $customer->gender === 'male' ? 'Homme' : 'Femme' }}
                                                @if($customer->birth_date)
                                                    • {{ $customer->birth_date->age }} ans
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $customer->email ?$customer->email : 'non renseigné' }}</div>
                                @if($customer->username)
                                    <div class="text-xs text-gray-500">@{{ $customer->username }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    {{-- @if($customer->detail && $customer->detail->phone) --}}
                                    @if($customer->detail)
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{-- {{ $customer->detail->phone }} --}}
                                            {{ $customer->detail->phone ? $customer->detail->phone : 'non renseigné' }}
                                        </div>
                                    @endif
                                    @if($customer->detail && $customer->detail->city)
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $customer->detail->city }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($customer->suspended_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Suspendu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Actif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $customer->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $customer->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button 
                                        wire:click="showCustomerDetails({{ $customer->id }})"
                                        class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                        title="Voir détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button 
                                        wire:click="showEditCustomer({{ $customer->id }})"
                                        class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                        title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button 
                                        wire:click="confirmDelete({{ $customer->id }})"
                                        class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                        title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="p-4 bg-gray-100 rounded-full">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun client trouvé</h3>
                                        <p class="text-gray-500">Il n'y a pas encore de clients correspondant à vos critères.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Création Client -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden animate-scale-in">
                <!-- Header du modal -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Nouveau Client</h3>
                        <button 
                            wire:click="closeCreateModal"
                            class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all duration-200 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <form wire:submit.prevent="createCustomer" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Informations personnelles -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Informations Personnelles
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom(s) <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="firstname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('firstname') border-red-500 @enderror">
                                        @error('firstname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom  <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="lastname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('lastname') border-red-500 @enderror">
                                        @error('lastname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input 
                                        type="email" 
                                        wire:model="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                                        <input 
                                            type="text" 
                                            wire:model="username"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('username') border-red-500 @enderror">
                                        @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                                        <select 
                                            wire:model="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                            <option value="">Sélectionner</option>
                                            <option value="male">Homme</option>
                                            <option value="female">Femme</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                        <input 
                                            type="date" 
                                            wire:model="birth_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('birth_date') border-red-500 @enderror">
                                        @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">N° CNI</label>
                                        <input 
                                            type="text" 
                                            wire:model="ncin"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('ncin') border-red-500 @enderror">
                                        @error('ncin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de contact -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Informations de Contact
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                    <input 
                                        type="tel" 
                                        wire:model="phone"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('phone') border-red-500 @enderror">
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                    <textarea 
                                        wire:model="address"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('address') border-red-500 @enderror"></textarea>
                                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                        <input 
                                            type="text" 
                                            wire:model="city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('city') border-red-500 @enderror">
                                        @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                        <input 
                                            type="text" 
                                            wire:model="postal_code"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('postal_code') border-red-500 @enderror">
                                        @error('postal_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                    <input 
                                        type="text" 
                                        wire:model="country"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('country') border-red-500 @enderror">
                                    @error('country') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeCreateModal"
                                class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                                Annuler
                            </button>
                            <button 
                                type="submit"
                                class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Créer le client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    

<!-- Modal Modification Client -->
    @if($showEditModal && $selectedCustomer)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden animate-scale-in">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Modifier le Client #{{ $selectedCustomer->id }}</h3>
                        <button 
                            wire:click="closeEditModal"
                            class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all duration-200 transform hover:scale-110">
                            {{-- <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 p-4 sm:p-6">
 
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg> --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <form wire:submit.prevent="updateCustomer" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Informations personnelles -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Informations Personnelles
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom(s) <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="firstname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('firstname') border-red-500 @enderror">
                                        @error('firstname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom  <span class="text-red-500">*</span></label>
                                        <input 
                                            type="text" 
                                            wire:model="lastname"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('lastname') border-red-500 @enderror">
                                        @error('lastname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input 
                                        type="email" 
                                        wire:model="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                                        <input 
                                            type="text" 
                                            wire:model="username"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('username') border-red-500 @enderror">
                                        @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                                        <select 
                                            wire:model="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                            <option value="">Sélectionner</option>
                                            <option value="male">Homme</option>
                                            <option value="female">Femme</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                        <input 
                                            type="date" 
                                            wire:model="birth_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('birth_date') border-red-500 @enderror">
                                        @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">N° CNI</label>
                                        <input 
                                            type="text" 
                                            wire:model="ncin"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('ncin') border-red-500 @enderror">
                                        @error('ncin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de contact -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Informations de Contact
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                    <input 
                                        type="tel" 
                                        wire:model="phone"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('phone') border-red-500 @enderror">
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                    <textarea 
                                        wire:model="address"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('address') border-red-500 @enderror"></textarea>
                                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                        <input 
                                            type="text" 
                                            wire:model="city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('city') border-red-500 @enderror">
                                        @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                        <input 
                                            type="text" 
                                            wire:model="postal_code"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('postal_code') border-red-500 @enderror">
                                        @error('postal_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                    <input 
                                        type="text" 
                                        wire:model="country"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('country') border-red-500 @enderror">
                                    @error('country') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeEditModal"
                                class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                                Annuler
                            </button>
                            <button 
                                type="submit"
                                class="cursor-pointer px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal Détails Client -->
    @if($showDetailsModal && $selectedCustomer)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden animate-scale-in">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Profil Client - {{ $selectedCustomer->firstname }} {{ $selectedCustomer->lastname }}</h3>
                        <button 
                            wire:click="closeDetailsModal"
                            class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all duration-200 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="p-6">
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                            <!-- Informations personnelles -->
                            <div class="xl:col-span-1 space-y-6">
                                <!-- Photo et infos de base -->
                                <div class="bg-gray-50 rounded-xl p-6 text-center">
                                    <div class="w-24 h-24 mx-auto bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mb-4">
                                        {{ $selectedCustomer->initials() }}
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $selectedCustomer->firstname }} {{ $selectedCustomer->lastname }}
                                    </h4>
                                    <p class="text-gray-600 mb-4">{{ $selectedCustomer->email }}</p>
                                    
                                    @if($selectedCustomer->suspended_at)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                            Compte suspendu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Compte actif
                                        </span>
                                    @endif
                                </div>

                                <!-- Détails personnels -->
                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                    <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Informations personnelles
                                    </h5>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-gray-600">N° Client:</span>
                                            <span class="font-medium">{{ $selectedCustomer->customer_number ?? 'N/A' }}</span>
                                        </div>
                                        @if($selectedCustomer->username)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Nom d'utilisateur:</span>
                                                <span class="font-medium">@{{ $selectedCustomer->username }}</span>
                                            </div>
                                        @endif
                                        @if($selectedCustomer->gender)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Genre:</span>
                                                <span class="font-medium">{{ $selectedCustomer->gender === 'male' ? 'Homme' : 'Femme' }}</span>
                                            </div>
                                        @endif
                                        @if($selectedCustomer->birth_date)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Date de naissance:</span>
                                                <span class="font-medium">{{ $selectedCustomer->birth_date->format('d/m/Y') }} ({{ $selectedCustomer->birth_date->age }} ans)</span>
                                            </div>
                                        @endif
                                        @if($selectedCustomer->ncin)
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">N° CNI:</span>
                                                <span class="font-medium">{{ $selectedCustomer->ncin }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Inscription:</span>
                                            <span class="font-medium">{{ $selectedCustomer->created_at->format('d/m/Y à H:i') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations de contact -->
                                @if($selectedCustomer->detail)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                                        <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            Contact
                                        </h5>
                                        
                                        <div class="space-y-3">
                                            @if($selectedCustomer->detail->phone)
                                                <div class="flex justify-between py-2 border-b border-gray-100">
                                                    <span class="text-gray-600">Téléphone:</span>
                                                    <span class="font-medium">{{ $selectedCustomer->detail->phone }}</span>
                                                </div>
                                            @endif
                                            @if($selectedCustomer->detail->address)
                                                <div class="py-2 border-b border-gray-100">
                                                    <span class="text-gray-600 block mb-1">Adresse:</span>
                                                    <span class="font-medium text-sm">{{ $selectedCustomer->detail->address }}</span>
                                                </div>
                                            @endif
                                            @if($selectedCustomer->detail->city)
                                                <div class="flex justify-between py-2 border-b border-gray-100">
                                                    <span class="text-gray-600">Ville:</span>
                                                    <span class="font-medium">{{ $selectedCustomer->detail->city }}</span>
                                                </div>
                                            @endif
                                            @if($selectedCustomer->detail->postal_code)
                                                <div class="flex justify-between py-2 border-b border-gray-100">
                                                    <span class="text-gray-600">Code postal:</span>
                                                    <span class="font-medium">{{ $selectedCustomer->detail->postal_code }}</span>
                                                </div>
                                            @endif
                                            @if($selectedCustomer->detail->country)
                                                <div class="flex justify-between py-2">
                                                    <span class="text-gray-600">Pays:</span>
                                                    <span class="font-medium">{{ $selectedCustomer->detail->country }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Historique des commandes -->
                            <div class="xl:col-span-2">
                                <div class="bg-white border border-gray-200 rounded-xl">
                                    <div class="px-6 py-4 border-b border-gray-200">
                                        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            Historique des Commandes ({{ $selectedCustomer->orders->count() }})
                                        </h5>
                                    </div>
                                    
                                    <div class="p-6">
                                        @if($selectedCustomer->orders->count() > 0)
                                            <div class="space-y-4">
                                                @foreach($selectedCustomer->orders->take(5) as $order)
                                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                                        <div class="flex items-center space-x-4">
                                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                                #{{ $order->id }}
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    Commande #{{ $order->id }}
                                                                    @if($order->tracking_no)
                                                                        <span class="text-gray-500">({{ $order->tracking_no }})</span>
                                                                    @endif
                                                                </p>
                                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="text-sm font-bold text-gray-900">
                                                                {{ number_format($order->orderItems->sum(function($item) { 
                                                                    return $item->quantity * $item->price; 
                                                                }), 0, ',', ' ') }} F
                                                            </p>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                                {{ $order->status_message === 'Terminé' ? 'bg-green-100 text-green-800' : 
                                                                   ($order->status_message === 'En cours de traitement' ? 'bg-yellow-100 text-yellow-800' : 
                                                                   ($order->status_message === 'Annulé' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                                                {{ $order->status_message ?? 'En cours' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                
                                                @if($selectedCustomer->orders->count() > 5)
                                                    <div class="text-center pt-4 border-t border-gray-200">
                                                        <p class="text-sm text-gray-500">
                                                            + {{ $selectedCustomer->orders->count() - 5 }} autres commandes
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Statistiques des commandes -->
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                                                <div class="text-center">
                                                    <p class="text-2xl font-bold text-purple-600">{{ $selectedCustomer->orders->count() }}</p>
                                                    <p class="text-sm text-gray-600">Commandes totales</p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-2xl font-bold text-green-600">
                                                        {{ number_format($selectedCustomer->orders->sum(function($order) {
                                                            return $order->orderItems->sum(function($item) {
                                                                return $item->quantity * $item->price;
                                                            });
                                                        }), 0, ',', ' ') }} F
                                                    </p>
                                                    <p class="text-sm text-gray-600">Montant total</p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-2xl font-bold text-blue-600">
                                                        {{ $selectedCustomer->orders->count() > 0 ? 
                                                           number_format($selectedCustomer->orders->sum(function($order) {
                                                               return $order->orderItems->sum(function($item) {
                                                                   return $item->quantity * $item->price;
                                                               });
                                                           }) / $selectedCustomer->orders->count(), 0, ',', ' ') : 0 }} F
                                                    </p>
                                                    <p class="text-sm text-gray-600">Panier moyen</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-8">
                                                <div class="p-3 bg-gray-100 rounded-full inline-block mb-3">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande</h3>
                                                <p class="text-gray-500">Ce client n'a pas encore passé de commande.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal Suppression -->
    @if($showDeleteModal && $customerToDelete)
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
                            Êtes-vous sûr de vouloir supprimer le client 
                            <strong>{{ $customerToDelete->firstname }} {{ $customerToDelete->lastname }}</strong> ?
                            Cette action est irréversible et supprimera toutes les données associées.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            wire:click="closeDeleteModal"
                            class="cursor-pointer flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button 
                            wire:click="deleteCustomer"
                            class="cursor-pointer flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- États de chargement -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
            <div>
                <p class="text-lg font-medium text-gray-900">Chargement en cours...</p>
                <p class="text-sm text-gray-500">Traitement des données clients</p>
            </div>
        </div>
    </div>


<!-- Styles personnalisés -->
<style>
    /* Animations fluides */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
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
        animation: fadeIn 0.2s ease-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.2s ease-out;
    }
    
    /* Effets de survol pour les lignes du tableau */
    .group:hover .opacity-0 {
        opacity: 1;
    }
    
    /* Animation des boutons */
    .transform {
        transition: transform 0.2s ease-in-out;
    }
    
    .transform:hover {
        transform: scale(1.05);
    }
    
    /* États de focus améliorés */
    .focus\:ring-2:focus {
        ring-width: 2px;
        ring-offset-width: 2px;
        outline: none;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Responsive improvements */
    @media (max-width: 640px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .modal-responsive {
            margin: 1rem;
            max-height: calc(100vh - 2rem);
        }
    }
    
    /* Animation pour les cartes de statistiques */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-animate {
        animation: slideUp 0.5s ease-out forwards;
    }
    
    /* Indicateurs de statut animés */
    .status-badge {
        transition: all 0.2s ease-in-out;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    /* Amélioration des formulaires */
    .form-input {
        transition: all 0.2s ease-in-out;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    
    .form-input.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    /* Animation des modales */
    .modal-backdrop {
        backdrop-filter: blur(4px);
        transition: backdrop-filter 0.2s ease-out;
    }
    
    /* Amélioration des tooltips */
    [title] {
        position: relative;
    }
    
    /* Effet de pulsation pour les éléments en chargement */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Styles pour les avatars */
    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Amélioration des boutons d'action */
    .action-button {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .action-button:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease-out;
        transform: translate(-50%, -50%);
    }
    
    .action-button:hover:before {
        width: 300px;
        height: 300px;
    }
    
    /* Grille responsive améliorée */
    .grid-auto-fit {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    /* États d'erreur pour les formulaires */
    .field-error {
        border-color: #f87171;
        background-color: #fef2f2;
    }
    
    .field-error:focus {
        border-color: #ef4444;
        ring-color: rgba(239, 68, 68, 0.2);
    }
    
    /* Animation pour les notifications */
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .notification-enter {
        animation: slideInRight 0.3s ease-out;
    }
    
    /* Amélioration des listes */
    .list-item {
        transition: all 0.2s ease-in-out;
        border-left: 3px solid transparent;
    }
    
    .list-item:hover {
        border-left-color: #3b82f6;
        background-color: #f8fafc;
        padding-left: 1.25rem;
    }
    
    /* Styles pour les badges de statut */
    .badge {
        display: inline-flex;
        align-items: center;
        font-weight: 500;
        font-size: 0.75rem;
        line-height: 1rem;
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        transition: all 0.15s ease-in-out;
    }
    
    .badge-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fed7aa;
    }
    
    .badge-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Responsive typography */
    @media (max-width: 768px) {
        .text-responsive-sm {
            font-size: 0.875rem;
        }
        
        .text-responsive-base {
            font-size: 1rem;
        }
        
        .text-responsive-lg {
            font-size: 1.125rem;
        }
    }
</style>

</div>

<!-- Scripts JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes de statistiques au chargement
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('card-animate');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observer les cartes de statistiques
        document.querySelectorAll('.transform').forEach(card => {
            observer.observe(card);
        });
        
        // Gestion des raccourcis clavier
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N pour nouveau client
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                Livewire.dispatch('showCreateCustomer');
            }
            
            // Echap pour fermer les modales
            if (e.key === 'Escape') {
                const modals = ['showCreateModal', 'showEditModal', 'showDetailsModal', 'showDeleteModal'];
                modals.forEach(modal => {
                    if (window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))[modal]) {
                        Livewire.dispatch('close' + modal.replace('show', '').replace('Modal', ''));
                    }
                });
            }
        });
        
        // Animation des tooltips
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-gray-900 text-white text-xs rounded py-1 px-2 z-50 pointer-events-none';
                tooltip.textContent = this.title;
                tooltip.style.top = '-30px';
                tooltip.style.left = '50%';
                tooltip.style.transform = 'translateX(-50%)';
                this.style.position = 'relative';
                this.appendChild(tooltip);
                this.removeAttribute('title');
            });
            
            element.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.absolute.bg-gray-900');
                if (tooltip) {
                    this.setAttribute('title', tooltip.textContent);
                    tooltip.remove();
                }
            });
        });
        
        // Amélioration de la saisie dans les formulaires
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('invalid', function() {
                this.classList.add('field-error');
            });
            
            input.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.classList.remove('field-error');
                }
            });
        });
        
        // Auto-resize des textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
        
        // Fonction pour formater les numéros de téléphone
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Supprime tous les caractères non numériques
                let value = this.value.replace(/\D/g, '');
                
                // Formate le numéro (exemple pour format sénégalais)
                if (value.length >= 2) {
                    value = value.replace(/(\d{2})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4');
                }
                
                this.value = value;
            });
        });
        
        // Sauvegarde automatique des brouillons (optionnel)
        let saveTimeout;
        const formInputs = document.querySelectorAll('#customerForm input, #customerForm textarea, #customerForm select');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    // Sauvegarder le brouillon en localStorage
                    const formData = new FormData(document.getElementById('customerForm'));
                    const data = Object.fromEntries(formData);
                    localStorage.setItem('customerFormDraft', JSON.stringify(data));
                }, 1000);
            });
        });
        
        // Charger le brouillon au chargement de la page
        const draft = localStorage.getItem('customerFormDraft');
        if (draft) {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input && !input.value) {
                    input.value = data[key];
                }
            });
        }
    });
    
    // Fonction globale pour nettoyer le brouillon
    window.clearCustomerDraft = function() {
        localStorage.removeItem('customerFormDraft');
    };
    
    // Gestion des notifications Livewire
    window.addEventListener('notify', event => {
        const { text, type, status } = event.detail;
        
        // Créer une notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm notification-enter ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
            type === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="font-medium">${text}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
        
        // Nettoyer le brouillon en cas de succès
        if (type === 'success') {
            clearCustomerDraft();
        }
    });
    
    // Optimisation des performances avec debounce
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Gestion optimisée du redimensionnement
    window.addEventListener('resize', debounce(() => {
        // Réajuster les éléments si nécessaire
        const modals = document.querySelectorAll('.fixed.inset-0');
        modals.forEach(modal => {
            if (window.innerWidth < 640) {
                modal.classList.add('modal-responsive');
            } else {
                modal.classList.remove('modal-responsive');
            }
        });
    }, 250));
</script>