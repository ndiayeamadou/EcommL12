<!-- resources\views\livewire\admin\pos\pos-interface.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header avec animation glassmorphism -->
    <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 border-b border-white/20 shadow-lg">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Point de Vente
                        </h1>
                        <p class="text-sm text-gray-500">Système POS Moderne</p>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques temps réel -->
            <div class="hidden md:flex items-center space-x-6">
                <div class="text-center p-4 bg-white/60 rounded-xl backdrop-blur-sm shadow-lg border border-white/30 transform hover:scale-105 transition-all duration-300">
                    <div class="text-2xl font-bold text-green-600">{{ count($cart) }}</div>
                    <div class="text-xs text-gray-600">Articles</div>
                </div>
                <div class="text-center p-4 bg-white/60 rounded-xl backdrop-blur-sm shadow-lg border border-white/30 transform hover:scale-105 transition-all duration-300">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($cartTotal, 0) }}</div>
                    <div class="text-xs text-gray-600">FCFA</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Zone Produits - Côté gauche -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Barre de recherche et filtres avec animations -->
            <div class="p-6 bg-white/70 backdrop-blur-sm border-b border-white/20">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <!-- Recherche avec icône animée -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Rechercher par nom, SKU..."
                            class="w-full pl-12 pr-4 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 shadow-lg hover:shadow-xl"
                        />
                    </div>

                    <!-- Filtres avec animations -->
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                wire:model.live="showOutOfStock"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all duration-200"
                            />
                            <span class="text-sm text-gray-600 group-hover:text-indigo-600 transition-colors">Rupture de stock</span>
                        </label>
                    </div>
                </div>

                <!-- Catégories avec pills animées -->
                <div class="mt-4 flex flex-wrap gap-2 overflow-x-auto pb-2">
                    <button 
                        wire:click="selectCategory('')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 whitespace-nowrap transform hover:scale-105 {{ !$selectedCategory ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-white/80 text-gray-600 hover:bg-indigo-50 border border-gray-200' }}"
                    >
                        <span class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Toutes</span>
                        </span>
                    </button>
                    
                    @foreach($categories as $category)
                        <button 
                            wire:click="selectCategory({{ $category->id }})"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 whitespace-nowrap transform hover:scale-105 {{ $selectedCategory == $category->id ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-white/80 text-gray-600 hover:bg-indigo-50 border border-gray-200' }}"
                        >
                            <span class="flex items-center space-x-2">
                                @if($category->icon)
                                    @php
                                        $iconComponent = match($category->icon) {
                                            'shopping-bag' => 'shopping-bag',
                                            'device-phone-mobile' => 'device-phone-mobile',
                                            'computer-desktop' => 'computer-desktop',
                                            'tshirt' => 'tshirt',
                                            'home' => 'home',
                                            'book-open' => 'book-open',
                                            'beaker' => 'beaker',
                                            default => 'squares-2x2'
                                        };
                                    @endphp
                                    
                                    @if($iconComponent === 'shopping-bag')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    @elseif($iconComponent === 'device-phone-mobile')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                        </svg>
                                    @elseif($iconComponent === 'computer-desktop')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                        </svg>
                                    @elseif($iconComponent === 'tshirt')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                        </svg>
                                    @elseif($iconComponent === 'home')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                    @elseif($iconComponent === 'book-open')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    @elseif($iconComponent === 'beaker')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                        </svg>
                                    @endif
                                @endif
                                <span>{{ $category->name }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grille des produits avec animations stagger -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" 
                     x-data="{ animated: false }" 
                     x-init="setTimeout(() => animated = true, 100)">
                    @forelse($products as $index => $product)
                        <div 
                            class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl border border-white/30 overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:scale-105 cursor-pointer"
                            style="animation-delay: {{ $index * 0.1 }}s"
                            x-show="animated"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            wire:click="addToCart({{ $product->id }})"
                        >
                            <!-- Image produit avec overlay gradient -->
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                {{-- @if($product->images && count(json_decode($product->images)) > 0)
                                    <img 
                                        src="{{ json_decode($product->images)[0] }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    />
                                @else --}}
                                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-indigo-300">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                {{-- @endif --}}
                                
                                <!-- Overlay avec boutons d'action -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                                        <div class="text-white">
                                            <p class="text-sm font-medium">Ajouter au panier</p>
                                            <p class="text-xs opacity-75">Stock: {{ $product->stock_quantity }}</p>
                                        </div>
                                        <div class="p-2 bg-white/20 backdrop-blur-sm rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Badge statut stock -->
                                @if($product->stock_quantity <= 5)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->stock_quantity == 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $product->stock_quantity == 0 ? 'Rupture' : 'Stock faible' }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Badge featured/promotion -->
                                @if($product->is_featured)
                                    <div class="absolute top-3 right-3">
                                        <div class="p-1.5 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations produit -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-1">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                                    </div>
                                </div>

                                <!-- Prix avec animation -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center space-x-2">
                                        @if($product->sale_price)
                                            <span class="text-lg font-bold text-green-600">
                                                {{ number_format($product->sale_price, 0) }} FCFA
                                            </span>
                                            <span class="text-sm text-gray-500 line-through">
                                                {{ number_format($product->price, 0) }} FCFA
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ number_format($product->price, 0) }} FCFA
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Stock avec indicateur coloré -->
                                    <div class="flex items-center space-x-1">
                                        <div class="w-2 h-2 rounded-full {{ $product->stock_quantity > 10 ? 'bg-green-400' : ($product->stock_quantity > 0 ? 'bg-orange-400' : 'bg-red-400') }}"></div>
                                        <span class="text-sm text-gray-600">{{ $product->stock_quantity }}</span>
                                    </div>
                                </div>

                                <!-- Barre de progression du stock -->
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div 
                                            class="h-1.5 rounded-full transition-all duration-300 {{ $product->stock_quantity > 10 ? 'bg-green-400' : ($product->stock_quantity > 5 ? 'bg-orange-400' : 'bg-red-400') }}"
                                            style="width: {{ min(100, ($product->stock_quantity / 20) * 100) }}%"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-16">
                            <div class="p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/30">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-400 mx-auto mb-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Aucun produit trouvé</h3>
                                <p class="text-gray-500 text-center">Essayez de modifier vos critères de recherche</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Panier - Côté droit -->
        <div class="w-96 bg-white/90 backdrop-blur-xl border-l border-white/20 shadow-2xl flex flex-col">
            <!-- En-tête du panier -->
            <div class="p-6 border-b border-gray-200/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Panier</h2>
                            <p class="text-sm text-gray-500">{{ count($cart) }} article(s)</p>
                        </div>
                    </div>
                    
                    @if(count($cart) > 0)
                        <button 
                            wire:click="clearCart"
                            class="p-2 text-gray-400 hover:text-red-600 transition-colors duration-200"
                            title="Vider le panier"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Items du panier -->
            <div class="flex-1 overflow-y-auto p-6">
                @if(count($cart) > 0)
                    <div class="space-y-4">
                        @foreach($cart as $key => $item)
                            <div class="group bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-white/30 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                                <div class="flex items-center space-x-3">
                                    <!-- Image produit -->
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover"/>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Détails produit -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $item['name'] }}
                                        </h4>
                                        <p class="text-sm text-gray-500">{{ $item['sku'] }}</p>
                                        <p class="text-sm font-medium text-indigo-600">
                                            {{ number_format($item['price'], 0) }} FCFA
                                        </p>
                                    </div>

                                    <!-- Contrôles quantité -->
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})"
                                            class="p-1 rounded-full bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 transition-all duration-200"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        
                                        <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                                        
                                        <button 
                                            wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})"
                                            class="p-1 rounded-full bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-600 transition-all duration-200"
                                            @if($item['quantity'] >= $item['stock_quantity']) disabled @endif
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>

                                        <button 
                                            wire:click="removeFromCart('{{ $key }}')"
                                            class="p-1 rounded-full bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 transition-all duration-200 ml-2"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Total ligne -->
                                <div class="mt-3 pt-3 border-t border-gray-200/50 flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Total ligne:</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ number_format($item['price'] * $item['quantity'], 0) }} FCFA
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center py-8">
                        <div class="p-6 bg-gray-50/80 backdrop-blur-sm rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-400 mx-auto mb-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Panier vide</h3>
                            <p class="text-gray-500">Ajoutez des produits pour commencer une vente</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Résumé et paiement -->
            @if(count($cart) > 0)
                <div class="p-6 border-t border-gray-200/50 bg-white/95 backdrop-blur-sm">
                    <!-- Totaux avec animations -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-medium">{{ number_format($cartSubtotal, 0) }} FCFA</span>
                        </div>
                        
                        @if($discountAmount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span>Remise:</span>
                                <span>-{{ number_format($discountAmount, 0) }} FCFA</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">TVA ({{ $taxRate }}%):</span>
                            <span class="font-medium">{{ number_format($taxAmount, 0) }} FCFA</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-lg font-bold border-t pt-3">
                            <span>Total:</span>
                            <span class="text-indigo-600">{{ number_format($cartTotal, 0) }} FCFA</span>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="space-y-3">
                        <button 
                            wire:click="showPayment"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center space-x-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            <span>Procéder au paiement</span>
                        </button>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                wire:click="$set('showCustomerModal', true)"
                                class="bg-white text-gray-700 py-2 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 flex items-center justify-center space-x-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="text-sm">Client</span>
                            </button>
                            
                            <button 
                                class="bg-white text-gray-700 py-2 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 flex items-center justify-center space-x-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-sm">Remise</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de paiement avec animations élégantes -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showPaymentModal') }">
            <div 
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <!-- Overlay -->
                <div class="fixed inset-0 backdrop-blur-sm bg-black/50" wire:click="$set('showPaymentModal', false)"></div>

                <!-- Modal -->
                <div 
                    class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white/95 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/30"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <!-- En-tête modal -->
                    <div class="px-8 py-6 border-b border-gray-200/50 bg-gradient-to-r from-indigo-600 to-purple-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 backdrop-blur-sm rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Finaliser la vente</h3>
                                    <p class="text-indigo-100">Montant à encaisser: {{ number_format($cartTotal, 0) }} FCFA</p>
                                </div>
                            </div>
                            <button 
                                wire:click="$set('showPaymentModal', false)"
                                class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenu modal -->
                    <div class="px-8 py-6">
                        <form wire:submit.prevent="processPayment">
                            <!-- Méthodes de paiement -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-4">Mode de paiement</label>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($paymentMethods as $method => $label)
                                        <label class="relative cursor-pointer">
                                            <input 
                                                type="radio" 
                                                wire:model.live="paymentMethod" 
                                                value="{{ $method }}"
                                                class="sr-only peer"
                                            />
                                            <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all duration-200 transform hover:scale-[1.02]">
                                                <div class="flex items-center space-x-3">
                                                    @if($method === 'cash')
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 peer-checked:text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                                        </svg>
                                                    @elseif($method === 'card')
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 peer-checked:text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                        </svg>
                                                    @elseif($method === 'mobile_money')
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 peer-checked:text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                                        </svg>
                                                    @elseif($method === 'bank_transfer')
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 peer-checked:text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 peer-checked:text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                    <span class="font-medium text-gray-900 peer-checked:text-indigo-900">{{ $label }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Montant encaissé -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Montant encaissé
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        wire:model.live="paidAmount"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-lg font-semibold"
                                        placeholder="0"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-gray-500 font-medium">FCFA</span>
                                    </div>
                                </div>
                                @error('paidAmount') 
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Monnaie à rendre -->
                            @if($changeAmount > 0)
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <span class="text-green-800 font-medium">Monnaie à rendre:</span>
                                        <span class="text-2xl font-bold text-green-600">
                                            {{ number_format($changeAmount, 0) }} FCFA
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Notes (optionnel)
                                </label>
                                <textarea 
                                    wire:model="notes"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Ajouter des notes..."
                                ></textarea>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex space-x-4">
                                <button 
                                    type="button"
                                    wire:click="$set('showPaymentModal', false)"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200"
                                >
                                    Annuler
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="processPayment"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                                >
                                    <span wire:loading.remove wire:target="processPayment">Finaliser la vente</span>
                                    <span wire:loading wire:target="processPayment" class="flex items-center space-x-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Traitement...</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Composant de notifications toast -->
    <div 
        x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            timeout: null
        }"
        x-on:show-notification.window="
            message = $event.detail.message;
            type = $event.detail.type || 'success';
            show = true;
            clearTimeout(timeout);
            timeout = setTimeout(() => show = false, 4000);
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-4 right-4 z-50 max-w-sm w-full"
    >
        <div 
            class="p-4 rounded-xl shadow-2xl backdrop-blur-xl border border-white/30"
            :class="{
                'bg-green-500/90 text-white': type === 'success',
                'bg-red-500/90 text-white': type === 'error', 
                'bg-yellow-500/90 text-white': type === 'warning',
                'bg-blue-500/90 text-white': type === 'info'
            }"
        >
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <template x-if="type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <template x-if="type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <template x-if="type === 'warning'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </template>
                    <template x-if="type === 'info'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </template>
                </div>
                <p class="text-sm font-medium" x-text="message"></p>
                <button @click="show = false" class="flex-shrink-0 ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 opacity-70 hover:opacity-100 transition-opacity">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Script pour gestion du scanner de code-barres -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let barcode = '';
            let scanTimeout;

            document.addEventListener('keydown', function(e) {
                // Ignorer si un input est focusé
                if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                    return;
                }

                clearTimeout(scanTimeout);
                
                if (e.key === 'Enter') {
                    if (barcode.length > 0) {
                        @this.call('addProductByBarcode', barcode);
                        barcode = '';
                    }
                } else if (e.key.length === 1) {
                    barcode += e.key;
                    
                    // Auto-submit après 100ms de pause
                    scanTimeout = setTimeout(() => {
                        if (barcode.length > 3) {
                            @this.call('addProductByBarcode', barcode);
                            barcode = '';
                        }
                    }, 100);
                }
            });
        });

        // Fonction pour imprimer le reçu
        window.addEventListener('print-receipt', function(event) {
            // Logique d'impression - peut être intégrée avec une imprimante thermique
            const saleId = event.detail.saleId;
            console.log('Imprimer reçu pour vente ID:', saleId);
            
            // Exemple d'intégration avec window.print()
            const printWindow = window.open(`/pos/receipt/${saleId}`, '_blank');
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        });
    </script>

    <!-- Styles CSS personnalisés pour les animations -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
            50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        /* Animation pour les items qui entrent */
        .cart-item-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Scrollbar personnalisée */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(243, 244, 246, 0.5);
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.5);
        }
        
        /* Effet glassmorphism */
        .glass {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        /* Animation des cartes produits */
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.05);
        }
        
        /* Animation des boutons */
        .btn-animate {
            position: relative;
            overflow: hidden;
        }
        
        .btn-animate::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-animate:hover::before {
            left: 100%;
        }
        
        /* Ligne de progression du stock */
        .stock-progress {
            position: relative;
            overflow: hidden;
        }
        
        .stock-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Responsive breakpoints personnalisés */
        @media (max-width: 768px) {
            .mobile-cart {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                transform: translateY(100%);
                transition: transform 0.3s ease-in-out;
                z-index: 40;
            }
            
            .mobile-cart.show {
                transform: translateY(0);
            }
        }
        
        /* Animation de chargement */
        .loading-dots::after {
            content: '';
            animation: loading-dots 1.4s ease-in-out infinite both;
        }
        
        @keyframes loading-dots {
            0%, 80%, 100% {
                opacity: 0;
            }
            40% {
                opacity: 1;
            }
        }
        
        /* Effet de typing pour les prix */
        .price-animate {
            position: relative;
        }
        
        .price-animate::after {
            content: '';
            position: absolute;
            right: -2px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: currentColor;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
    </style>
</div>