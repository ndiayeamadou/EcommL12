<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 p-4 sm:p-6">
    <!-- Header avec actions -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="mb-4 lg:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux commandes
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Commande #{{ $order->id }}
                    @if($order->tracking_no)
                        <span class="text-xl text-gray-600 font-normal">({{ $order->tracking_no }})</span>
                    @endif
                </h1>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    <div class="flex items-center space-x-2">
                        @php
                            $statusColors = [
                                'En cours de traitement' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Confirmé' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'En préparation' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'Expédié' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'En livraison' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'Livré' => 'bg-green-100 text-green-800 border-green-200',
                                'Terminé' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'Annulé' => 'bg-red-100 text-red-800 border-red-200',
                                'Remboursé' => 'bg-gray-100 text-gray-800 border-gray-200',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColors[$order->status_message] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                            <div class="w-2 h-2 rounded-full {{ str_replace('bg-', 'bg-', $statusColors[$order->status_message] ?? 'bg-gray-400') }} mr-2 animate-pulse"></div>
                            {{ $order->status_message }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <button 
                    wire:click="generatePDF"
                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Générer PDF
                </button>
                
                @if($order->email)
                    {{-- <button 
                        wire:click="sendOrderEmail"
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Envoyer par Email
                    </button> --}}
                @endif
                
                <button 
                    wire:click="showUpdateStatus"
                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Changer Statut
                </button>
                
                <button 
                    wire:click="showEditOrder"
                    class="cursor-pointer inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </button>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Commande</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($orderTotal, 0, ',', ' ') }} F</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Articles</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalItems }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Mode Paiement</p>
                        <p class="text-lg font-bold text-purple-600">
                            @php
                                $paymentLabels = [
                                    'cash' => 'Espèces',
                                    'card' => 'Carte',
                                    'mobile' => 'Mobile Money',
                                    'bank_transfer' => 'Virement',
                                ];
                            @endphp
                            {{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Client</p>
                        <p class="text-lg font-bold text-orange-600">{{ $order->fullname }}</p>
                        @if($order->user)
                            <p class="text-xs text-gray-500">#{{ $order->user->customer_number ?? $order->user->id }}</p>
                        @endif
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Détails de la commande -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Articles commandés -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Articles Commandés ({{ $order->orderItems->count() }})
                        </h3>
                        <button 
                            wire:click="showAddItem"
                            class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter
                        </button>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200 group">
                            <div class="flex items-start space-x-4">
                                <!-- Image du produit -->
                                <div class="flex-shrink-0">
                                    @if($item->product && $item->product->images->count() > 0)
                                        <img 
                                            src="{{ $item->product->images->first()->image_url }}" 
                                            alt="{{ $item->product->name }}"
                                            class="w-20 h-20 object-cover rounded-xl border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border border-gray-200">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Détails du produit -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900 mb-1">
                                                {{ $item->product ? $item->product->name : 'Produit supprimé' }}
                                            </h4>
                                            @if($item->product)
                                                <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product->sku }}</p>
                                            @endif
                                            
                                            @if($item->productColor && $item->productColor->color)
                                                <div class="flex items-center mb-2">
                                                    <span class="text-sm text-gray-600 mr-2">Couleur:</span>
                                                    <div class="flex items-center">
                                                        <div class="w-4 h-4 rounded-full border border-gray-300 mr-2" 
                                                             style="background-color: {{ $item->productColor->color->hex_code }}"></div>
                                                        <span class="text-sm font-medium text-gray-700">{{ $item->productColor->color->name }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center space-x-4 text-sm">
                                                <span class="text-gray-600">Quantité: <strong class="text-gray-900">{{ number_format($item->quantity) }}</strong></span>
                                                <span class="text-gray-600">Prix unitaire: <strong class="text-gray-900">{{ number_format($item->price, 0, ',', ' ') }} F</strong></span>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right ml-4">
                                            <div class="text-xl font-bold text-green-600 mb-2">
                                                {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F
                                            </div>
                                            <button 
                                                wire:click="confirmDeleteItem({{ $item->id }})"
                                                class="cursor-pointer opacity-0 group-hover:opacity-100 inline-flex items-center px-2 py-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Total -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total de la commande</span>
                        <span class="text-2xl font-bold text-green-600">{{ number_format($orderTotal, 0, ',', ' ') }} F</span>
                    </div>
                </div>
            </div>

            <!-- Historique des statuts -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Historique des Statuts
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($statusHistory as $index => $history)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $history['status'] }}</h4>
                                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($history['date'])->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    @if(!empty($history['note']))
                                        <p class="text-sm text-gray-600 mt-1">{{ $history['note'] }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">Par {{ $history['user'] }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div class="ml-4 w-0.5 h-4 bg-gray-200"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations client et commande -->
        <div class="space-y-6">
            <!-- Informations client -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informations Client
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl mb-3">
                            @if($order->user)
                                {{ $order->user->initials() }}
                            @else
                                {{ substr($order->fullname, 0, 2) }}
                            @endif
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900">{{ $order->fullname }}</h4>
                        @if($order->user && $order->user->customer_number)
                            <p class="text-sm text-gray-500">Client #{{ $order->user->customer_number }}</p>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        @if($order->email)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">{{ $order->email }}</span>
                            </div>
                        @endif
                        
                        @if($order->phone)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">{{ $order->phone }}</span>
                            </div>
                        @endif
                        
                        @if($order->address)
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <div>{{ $order->address }}</div>
                                    @if($order->city || $order->postal_code)
                                        <div class="mt-1">
                                            {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations de paiement -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Informations de Paiement
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Mode de paiement:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $order->payment_mode === 'cash' ? 'bg-green-100 text-green-800' : 
                                   ($order->payment_mode === 'card' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->payment_mode === 'mobile' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $paymentLabels[$order->payment_mode] ?? $order->payment_mode }}
                            </span>
                        </div>
                        
                        @if($order->payment_id)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">ID Transaction:</span>
                                <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $order->payment_id }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-base font-semibold text-gray-900">Total payé:</span>
                            <span class="text-xl font-bold text-green-600">{{ number_format($orderTotal, 0, ',', ' ') }} F</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de livraison -->
            @if($order->agent_id)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Informations de Gestion
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Agent responsable:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $order->agent->name ?? 'Agent #'.$order->agent_id }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Interface:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $order->back == 0 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $order->back == 0 ? 'Backoffice' : 'Frontend' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Modification Commande -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden animate-scale-in">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Modifier la Commande #{{ $order->id }}</h3>
                        <button 
                            wire:click="closeEditModal"
                            class="cursor-pointer p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all duration-200 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <form wire:submit.prevent="updateOrder" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Informations client -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informations Client</h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                                    <input 
                                        type="text" 
                                        wire:model="fullname"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('fullname') border-red-500 @enderror">
                                    @error('fullname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input 
                                        type="email" 
                                        wire:model="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                    <input 
                                        type="tel" 
                                        wire:model="phone"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('phone') border-red-500 @enderror">
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Adresse et paiement -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Livraison & Paiement</h4>
                                
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
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                        <input 
                                            type="text" 
                                            wire:model="postal_code"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement *</label>
                                    <select 
                                        wire:model="payment_mode"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('payment_mode') border-red-500 @enderror">
                                        <option value="cash">Espèces</option>
                                        <option value="card">Carte bancaire</option>
                                        <option value="mobile">Mobile Money</option>
                                        <option value="bank_transfer">Virement bancaire</option>
                                    </select>
                                    @error('payment_mode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Transaction</label>
                                    <input 
                                        type="text" 
                                        wire:model="payment_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
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
                                class="cursor-pointer px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
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


    <!-- Modal Changement de Statut -->
    @if($showStatusModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-xl font-bold text-gray-900">Changer le Statut</h3>
                </div>

                <form wire:submit.prevent="updateStatus" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut *</label>
                            <select 
                                wire:model="status_message"
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('status_message') border-red-500 @enderror">
                                @foreach($availableStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status_message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note (optionnel)</label>
                            <textarea 
                                wire:model="status_note"
                                rows="3"
                                placeholder="Ajouter une note sur ce changement de statut..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                        <button 
                            type="button"
                            wire:click="closeStatusModal"
                            class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button 
                            type="submit"
                            class="cursor-pointer px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Ajout d'Article -->
    @if($showAddItemModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full animate-scale-in">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-green-50">
                    <h3 class="text-xl font-bold text-gray-900">Ajouter un Article</h3>
                </div>

                <form wire:submit.prevent="addOrderItem" class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produit *</label>
                            <select 
                                wire:model.live="product_id"
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('product_id') border-red-500 @enderror">
                                <option value="0">Sélectionner un produit</option>
                                @foreach($availableProducts as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }} - {{ number_format($product['price'], 0, ',', ' ') }} F</option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Couleur (optionnel)</label>
                            <select 
                                wire:model="product_color_id"
                                class="cursor-pointer w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="0">Pas de couleur spécifique</option>
                                @if($product_id > 0)
                                    @php $selectedProduct = collect($availableProducts)->firstWhere('id', $product_id); @endphp
                                    @if($selectedProduct && isset($selectedProduct['colors']))
                                        @foreach($selectedProduct['colors'] as $color)
                                            <option value="{{ $color['id'] }}">{{ $color['color']['name'] ?? 'Couleur' }}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité *</label>
                            <input 
                                type="number" 
                                wire:model="quantity"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('quantity') border-red-500 @enderror">
                            @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire *</label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    wire:model="price"
                                    min="0"
                                    step="0.01"
                                    class="w-full pl-3 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('price') border-red-500 @enderror">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">F</span>
                            </div>
                            @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($product_id > 0 && $quantity > 0 && $price > 0)
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total article:</span>
                                <span class="text-lg font-bold text-green-600">{{ number_format($quantity * $price, 0, ',', ' ') }} F</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                        <button 
                            type="button"
                            wire:click="closeAddItemModal"
                            class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button 
                            type="submit"
                            class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter l'article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Suppression d'Article -->
    @if($showDeleteItemModal && $selectedItem)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Supprimer l'article</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer cet article de la commande ?
                        </p>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-900">{{ $selectedItem->product->name ?? 'Produit' }}</p>
                            <p class="text-xs text-gray-500">Quantité: {{ $selectedItem->quantity }} - Prix: {{ number_format($selectedItem->price * $selectedItem->quantity, 0, ',', ' ') }} F</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            wire:click="closeDeleteItemModal"
                            class="cursor-pointer flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                        <button 
                            wire:click="deleteOrderItem"
                            class="cursor-pointer flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- État de chargement -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
            <div>
                <p class="text-lg font-medium text-gray-900">Traitement en cours...</p>
                <p class="text-sm text-gray-500">Veuillez patienter</p>
            </div>
        </div>
    </div>


<!-- Styles personnalisés -->
<style>
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
    
    .group:hover .opacity-0 {
        opacity: 1;
    }
    
    /* Animation des statuts */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
</style>

</div>