<!-- resources/views/livewire/admin/products/products-index.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header avec animations -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="animate-pulse-slow">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 animate-fade-in">
                            Gestion des Produits
                        </h1>
                        <p class="text-gray-600 animate-slide-up-delay">
                            {{ $products->total() }} produits au total
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.products.create') }}" 
                       class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Nouveau Produit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche avec animations -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-slide-up">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <div class="relative group">
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               placeholder="Rechercher par nom, SKU ou description..."
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtre Statut -->
                <div>
                    <select wire:model.live="statusFilter"
                            class="w-full py-3 px-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="draft">Brouillon</option>
                    </select>
                </div>

                <!-- Filtre Catégorie -->
                <div>
                    <select wire:model.live="categoryFilter"
                            class="w-full py-3 px-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <option value="">Toutes catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre Marque -->
                <div>
                    <select wire:model.live="brandFilter"
                            class="w-full py-3 px-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <option value="">Toutes marques</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Actions en lot -->
            @if(count($selectedProducts) > 0)
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200 animate-slide-down">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                        <span class="text-blue-800 font-medium">
                            {{ count($selectedProducts) }} produit(s) sélectionné(s)
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="bulkUpdateStatus('active')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7z"/>
                                </svg>
                                <span>Activer</span>
                            </button>
                            <button wire:click="bulkUpdateStatus('inactive')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                                <span>Désactiver</span>
                            </button>
                            <button wire:click="bulkDelete"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ces produits ?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Grille des produits -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Options d'affichage -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ $products->total() }} résultat(s)</span>
                
                <!-- Toggle vue -->
                <div class="flex bg-white/80 rounded-lg p-1 border border-gray-200">
                    <button wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded-md {{ $viewMode === 'grid' ? 'bg-blue-100 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded-md {{ $viewMode === 'list' ? 'bg-blue-100 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tri -->
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Trier par:</span>
                <select wire:model.live="sortField"
                        class="text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500/20">
                    <option value="created_at">Date de création</option>
                    <option value="name">Nom</option>
                    <option value="price">Prix</option>
                    <option value="stock_quantity">Stock</option>
                </select>
                <button wire:click="$set('sortDirection', '{{ $sortDirection === 'asc' ? 'desc' : 'asc' }}')"
                        class="p-1 text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </button>
            </div>
        </div>

        @if($viewMode === 'grid')
            <!-- Vue Grille -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $index => $product)
                    <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-white/50 animate-fade-in-up"
                         style="animation-delay: {{ $index * 0.1 }}s">
                        
                        <!-- Image du produit -->
                        <div class="relative overflow-hidden rounded-t-2xl">
                            <img src="{{ $product->primary_image_url }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            <!-- Overlay avec actions -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-lg transition-all duration-200 transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.show', $product) }}" target="_blank"
                                   class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-lg transition-all duration-200 transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                @if($product->is_featured)
                                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold animate-bounce">
                                        Vedette
                                    </span>
                                @endif
                                @if($product->is_on_sale)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                                @if($product->status === 'draft')
                                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Brouillon
                                    </span>
                                @endif
                            </div>

                            <!-- Checkbox de sélection -->
                            <div class="absolute top-3 right-3">
                                <input type="checkbox" 
                                       wire:model.live="selectedProducts" 
                                       value="{{ $product->id }}"
                                       class="w-5 h-5 text-blue-600 rounded border-2 border-white/80 focus:ring-blue-500 transition-all">
                            </div>
                        </div>

                        <!-- Contenu de la carte -->
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-bold text-gray-900 text-lg line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                <div class="flex items-center space-x-1 ml-2">
                                    <!-- Toggle vedette -->
                                    <button wire:click="toggleFeatured({{ $product->id }})"
                                            class="p-1 rounded-lg transition-all duration-200 {{ $product->is_featured ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-400 hover:text-yellow-500' }}">
                                        <svg class="w-5 h-5" fill="{{ $product->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Catégorie et Marque -->
                            <div class="flex items-center space-x-2 mb-2">
                                @if($product->category)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                @if($product->brand)
                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $product->short_description ?: Str::limit($product->description, 80) }}
                            </p>

                            <!-- Prix -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ $product->formatted_price }}
                                    </span>
                                    @if($product->is_on_sale)
                                        <span class="text-sm text-gray-500 line-through">
                                            {{ $product->formatted_original_price }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations complémentaires -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Stock: {{ $product->stock_quantity }}</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>{{ $product->sku }}</span>
                                </span>
                            </div>

                            <!-- Statut et actions -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="toggleStatus({{ $product->id }})"
                                            class="px-3 py-1 rounded-full text-xs font-semibold transition-all duration-200 {{ $product->status === 'active' ? 'bg-green-100 text-green-800 hover:bg-green-200' : ($product->status === 'inactive' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200') }}">
                                        {{ ucfirst($product->status) }}
                                    </button>
                                </div>
                                
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button wire:click="deleteProduct({{ $product->id }})"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 animate-fade-in">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h3>
                            <p class="text-gray-600 mb-6">Aucun produit ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('admin.products.create') }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Créer le premier produit
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Vue Liste -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" 
                                           wire:model.live="selectAll"
                                           class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                               wire:model.live="selectedProducts" 
                                               value="{{ $product->id }}"
                                               class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $product->category->name ?? '-' }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->brand->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->formatted_price }}</div>
                                        @if($product->is_on_sale)
                                            <div class="text-sm text-gray-500 line-through">{{ $product->formatted_original_price }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 {{ $product->stock_quantity > 10 ? 'text-green-600' : ($product->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $product->stock_quantity }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="toggleStatus({{ $product->id }})"
                                                class="px-2 py-1 text-xs font-medium rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : ($product->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($product->status) }}
                                        </button>
                                        @if($product->is_featured)
                                            <span class="ml-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Vedette</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button wire:click="deleteProduct({{ $product->id }})"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"
                                                    class="text-red-600 hover:text-red-900 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                            <button wire:click="toggleFeatured({{ $product->id }})"
                                                    class="{{ $product->is_featured ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-400 hover:text-yellow-600' }} transition-colors">
                                                <svg class="w-4 h-4" fill="{{ $product->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="max-w-md mx-auto">
                                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h3>
                                            <p class="text-gray-600 mb-6">Aucun produit ne correspond à vos critères de recherche.</p>
                                            <a href="{{ route('admin.products.create') }}" 
                                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Créer le premier produit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8 flex justify-center animate-fade-in-up">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-4">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slide-up {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @keyframes slide-down {
            from { 
                opacity: 0; 
                transform: translateY(-10px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @keyframes fade-in-up {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .animate-fade-in { animation: fade-in 0.8s ease-out; }
        .animate-slide-up { animation: slide-up 0.6s ease-out; }
        .animate-slide-up-delay { animation: slide-up 0.8s ease-out; }
        .animate-slide-down { animation: slide-down 0.4s ease-out; }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out; }
        .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Pagination personnalisée */
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination .page-link {
            @apply px-3 py-2 text-sm font-medium text-gray-700 bg-white/70 border border-gray-300 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 transition-all duration-200;
        }

        .pagination .page-item.active .page-link {
            @apply bg-blue-600 text-white border-blue-600;
        }

        .pagination .page-item.disabled .page-link {
            @apply bg-gray-100 text-gray-400 cursor-not-allowed;
        }
    </style>
</div>

@push('scripts')
<script>
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);

    // Observer tous les éléments avec des animations
    document.querySelectorAll('.animate-fade-in-up').forEach(el => {
        observer.observe(el);
    });

    // Notification toast pour les messages
    Livewire.on('product-updated', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('product-deleted', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('products-bulk-deleted', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('products-bulk-updated', (data) => {
        showToast(data.message, 'success');
    });

    function showToast(message, type) {
        // Créer un élément toast
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animation d'apparition
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Suppression après 3 secondes
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Gestion du changement de vue
    document.addEventListener('DOMContentLoaded', function() {
        // La variable viewMode est maintenant gérée par Livewire
        console.log('Mode d\'affichage:', @this.viewMode);
    });
</script>
@endpush