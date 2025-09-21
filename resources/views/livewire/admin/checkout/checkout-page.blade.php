<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutApp()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec bouton retour et progression -->
        <div class="mb-10">
            <!-- Bouton retour -->
            <div class="mb-4">
                <a href="{{ route('admin.pos.sales') }}" class="inline-flex items-center text-primary hover:text-purple-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la vente
                </a>
            </div>
            
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser votre commande</h1>
                <p class="text-gray-600">Remplissez les informations pour compléter l'achat</p>
                
                <!-- Barre de progression -->
                <div class="mt-8 max-w-md mx-auto">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary transition-all duration-500" :style="'width: ' + progressWidth + '%'"></div>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span :class="{'font-bold text-primary': currentStep >= 1}">Information</span>
                        <span :class="{'font-bold text-primary': currentStep >= 2}">Livraison</span>
                        <span :class="{'font-bold text-primary': currentStep >= 3}">Paiement</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Formulaire -->
            <div class="w-full lg:w-7/12">
                <!-- Recherche de client pour les admins -->
                @if($isAdmin && !Auth::user()->isCustomer())
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6" x-show.transition="currentStep === 1">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Rechercher ou créer un client
                    </h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher un client</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                wire:model.live="customerSearch" 
                                wire:keydown.debounce.500ms="searchCustomers"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                                placeholder="Nom, email ou numéro client..."
                            />
                        </div>
                    </div>
                    
                    <!-- Résultats de recherche -->
                    @if(count($searchResults) > 0)
                    <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                        @foreach($searchResults as $customer)
                        <div 
                            wire:click="selectCustomer({{ $customer->id }})" 
                            class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 transition-colors flex items-center justify-between"
                        >
                            <div>
                                <div class="font-medium">{{ $customer->firstname }} {{ $customer->lastname }}</div>
                                <div class="text-sm text-gray-600">{{ $customer->email }}</div>
                                <div class="text-xs text-gray-500">N°: {{ $customer->customer_number }}</div>
                            </div>
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Client sélectionné -->
                    @if($customerId)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-green-800">Client sélectionné</p>
                                <p class="text-sm text-green-600">{{ $selectedCustomer->firstname }} {{ $selectedCustomer->lastname }}</p>
                            </div>
                            <button wire:click="clearCustomerSelection" class="text-green-600 hover:text-green-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <div class="text-center text-gray-500 text-sm py-2">
                        <span class="bg-white px-2">OU</span>
                    </div>
                    
                    <p class="text-center text-sm text-gray-600">Créer un nouveau client</p>
                </div>
                @endif
                
                <!-- Étape 1: Informations personnelles -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6" x-show.transition="currentStep === 1">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-3">1</div>
                        Informations personnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                            <input type="text" wire:model="firstName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                            @error('firstName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                            <input type="text" wire:model="lastName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                            @error('lastName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                        <input type="tel" wire:model="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button @click="currentStep = 2" class="cursor-pointer bg-purple-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-purple-700 transition-colors flex items-center shadow-sm hover:shadow-md">
                            Suivant
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Étape 2: Adresse de livraison -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6" x-show.transition="currentStep === 2">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-3">2</div>
                        Adresse de livraison
                    </h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                        <input type="text" wire:model="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="Votre adresse complète">
                        @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                            <input type="text" wire:model="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                            @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code postal *</label>
                            <input type="text" wire:model="postalCode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                            @error('postalCode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pays *</label>
                            <select wire:model="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                <option value="Sénégal">Sénégal</option>
                                <option value="France">France</option>
                                <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                <option value="Mali">Mali</option>
                                <option value="Guinée">Guinée</option>
                            </select>
                            @error('country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button @click="currentStep = 1" class="cursor-pointer text-gray-600 px-6 py-2 rounded-lg font-medium hover:text-gray-800 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Retour
                        </button>
                        <button @click="currentStep = 3" class="cursor-pointer bg-purple-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-purple-700 transition-colors flex items-center shadow-sm hover:shadow-md">
                            Suivant
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Étape 3: Paiement -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6" x-show.transition="currentStep === 3">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center mr-3">3</div>
                        Mode de paiement
                    </h2>
                    
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Espèces (option par défaut) -->
                            <div 
                                class="border-2 rounded-lg p-4 cursor-pointer transition-all hover:border-primary"
                                :class="{'border-primary bg-purple-50': paymentMethod === 'cash', 'border-gray-300': paymentMethod !== 'cash'}"
                                @click="paymentMethod = 'cash'"
                            >
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3"
                                         :class="{'border-primary bg-primary': paymentMethod === 'cash', 'border-gray-400': paymentMethod !== 'cash'}">
                                        <div x-show="paymentMethod === 'cash'" class="w-3 h-3 rounded-full bg-white"></div>
                                    </div>
                                    <span class="font-medium">Espèces</span>
                                </div>
                                <div class="mt-3 text-green-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Carte de crédit -->
                            <div 
                                class="border-2 rounded-lg p-4 cursor-pointer transition-all hover:border-primary"
                                :class="{'border-primary bg-purple-50': paymentMethod === 'card', 'border-gray-300': paymentMethod !== 'card'}"
                                @click="paymentMethod = 'card'"
                            >
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3"
                                         :class="{'border-primary bg-primary': paymentMethod === 'card', 'border-gray-400': paymentMethod !== 'card'}">
                                        <div x-show="paymentMethod === 'card'" class="w-3 h-3 rounded-full bg-white"></div>
                                    </div>
                                    <span class="font-medium">Carte de crédit</span>
                                </div>
                                <div class="mt-3 flex space-x-2">
                                    <div class="w-10 h-6 bg-blue-100 rounded-sm flex items-center justify-center text-blue-800 text-xs font-bold">VISA</div>
                                    <div class="w-10 h-6 bg-red-100 rounded-sm flex items-center justify-center text-red-800 text-xs font-bold">MC</div>
                                </div>
                            </div>
                            
                            <!-- Wave Money -->
                            <div 
                                class="border-2 rounded-lg p-4 cursor-pointer transition-all hover:border-primary"
                                :class="{'border-primary bg-purple-50': paymentMethod === 'wave', 'border-gray-300': paymentMethod !== 'wave'}"
                                @click="paymentMethod = 'wave'"
                            >
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3"
                                         :class="{'border-primary bg-primary': paymentMethod === 'wave', 'border-gray-400': paymentMethod !== 'wave'}">
                                        <div x-show="paymentMethod === 'wave'" class="w-3 h-3 rounded-full bg-white"></div>
                                    </div>
                                    <span class="font-medium">Wave</span>
                                </div>
                                <div class="mt-3 text-purple-600">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M21 6h-4v-1c0-1.654-1.346-3-3-3h-4c-1.654 0-3 1.346-3 3v1h-4c-1.654 0-3 1.346-3 3v9c0 1.654 1.346 3 3 3h16c1.654 0 3-1.346 3-3v-9c0-1.654-1.346-3-3-3zm-12-1c0-.551.449-1 1-1h4c.551 0 1 .449 1 1v1h-6v-1zm11 13c0 .551-.449 1-1 1h-16c-.551 0-1-.449-1-1v-9c0-.551.449-1 1-1h16c.551 0 1 .449 1 1v9zm-10-4c0-.552-.448-1-1-1s-1 .448-1 1 .448 1 1 1 1-.448 1-1zm5 0c0-.552-.448-1-1-1s-1 .448-1 1 .448 1 1 1 1-.448 1-1zm-10-3c0-.552-.448-1-1-1s-1 .448-1 1 .448 1 1 1 1-.448 1-1zm5 0c0-.552-.448-1-1-1s-1 .448-1 1 .448 1 1 1 1-.448 1-1zm5 0c0-.552-.448-1-1-1s-1 .448-1 1 .448 1 1 1 1-.448 1-1z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Orange Money -->
                            <div 
                                class="border-2 rounded-lg p-4 cursor-pointer transition-all hover:border-primary"
                                :class="{'border-primary bg-purple-50': paymentMethod === 'orange', 'border-gray-300': paymentMethod !== 'orange'}"
                                @click="paymentMethod = 'orange'"
                            >
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center mr-3"
                                         :class="{'border-primary bg-primary': paymentMethod === 'orange', 'border-gray-400': paymentMethod !== 'orange'}">
                                        <div x-show="paymentMethod === 'orange'" class="w-3 h-3 rounded-full bg-white"></div>
                                    </div>
                                    <span class="font-medium">Orange Money</span>
                                </div>
                                <div class="mt-3 text-orange-600">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M17.5 12.5c0-.828-.672-1.5-1.5-1.5s-1.5.672-1.5 1.5.672 1.5 1.5 1.5 1.5-.672 1.5-1.5zm-11-1.5c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5zm5.5-1c-2.761 0-5 2.239-5 5s2.239 5 5 5 5-2.239 5-5-2.239-5-5-5zm0-3c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Champs pour carte de crédit (affichés seulement si sélectionnés) -->
                    <div x-show="paymentMethod === 'card'" x-transition>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                            <div class="relative">
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="1234 5678 9012 3456">
                                <div class="absolute right-3 top-2.5">
                                    <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="MM/AA">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="123">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titulaire de la carte</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="Nom comme sur la carte">
                        </div>
                    </div>
                    
                    <!-- Message pour mobile money -->
                    <div x-show="paymentMethod === 'wave' || paymentMethod === 'orange'" x-transition class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                            </svg>
                            <p class="text-blue-800 text-sm">Le client effectuera le paiement via son application mobile.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center mb-6">
                        <input type="checkbox" id="terms" wire:model="termsAccepted" class="w-4 h-4 text-primary rounded focus:ring-primary border-gray-300">
                        <label for="terms" class="ml-2 text-sm text-gray-600">J'accepte les <a href="#" class="text-primary hover:underline">conditions générales</a></label>
                    </div>
                    @error('termsAccepted') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    
                    <div class="flex justify-between">
                        <button @click="currentStep = 2" class="cursor-pointer text-gray-600 px-6 py-2 rounded-lg font-medium hover:text-gray-800 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Retour
                        </button>
                        <button 
                            wire:click="completeOrder" 
                            class="cursor-pointer bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="{'opacity-75 cursor-not-allowed': !$termsAccepted}"
                            :disabled="!$termsAccepted"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Finaliser la commande
                        </button>
                    </div>
                </div>
            </div>

            <!-- Récapitulatif de la commande -->
            <div class="w-full lg:w-5/12">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <h2 class="text-xl font-semibold mb-6 flex items-center">
                        <svg class="w-6 h-6 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Récapitulatif
                    </h2>
                    
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Produit</span>
                            <span>Sous-total</span>
                        </div>
                        
                        @foreach($cart as $item)
                        <div class="flex justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-md overflow-hidden mr-3 flex-shrink-0">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-sm">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                                    @if($item->product_color_id && $item->productColor)
                                        <p class="text-xs text-gray-500">{{ $item->productColor->name }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="font-medium">{{ number_format(($item->product->sale_price ?? $item->product->price) * $item->quantity, 0, ',', ' ') }} F</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total</span>
                            <span>{{ number_format($totalPrice, 0, ',', ' ') }} F</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="text-green-600">0 F</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Taxes</span>
                            <span>0 F</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-primary">{{ number_format($totalPrice, 0, ',', ' ') }} F</span>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-800">Livraison offerte !</p>
                                <p class="text-xs text-green-600">Frais de livraison gratuits</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkoutApp() {
            return {
                currentStep: 1,
                paymentMethod: 'cash', // Paiement en espèces par défaut
                termsAccepted: false,
                
                // Calcul de la largeur de la barre de progression
                get progressWidth() {
                    return this.currentStep * 33.33;
                },
                
                init() {
                    // Suivre les changements de termsAccepted pour le bouton de paiement
                    this.$watch('termsAccepted', (value) => {
                        // Cette fonction est nécessaire pour la réactivité avec Livewire
                    });
                }
            }
        }
    </script>
</div>