<div class="flex flex-col lg:flex-row h-screen bg-gray-100">
    <!-- Sidebar - Product List -->
    <div class="w-full lg:w-3/5 xl:w-2/3 h-full overflow-hidden flex flex-col">
        <!-- Header with search and filters -->
        <div class="bg-white p-4 shadow-sm">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                            placeholder="Rechercher un produit..."
                        >
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="w-full md:w-48">
                    <select wire:model.live="categoryFilter" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Items per page -->
                <div class="w-full md:w-24">
                    <select wire:model.live="perPage" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="48">48</option>
                        <option value="96">96</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-4">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <div 
                            wire:click="showProductDetails({{ $product->id }})" 
                            class="bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transform transition-transform hover:scale-105"
                        >
                            <div class="h-40 bg-gray-200 overflow-hidden">
                                @if($product->primaryImage)
                                    <img 
                                        src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover"
                                    >
                                @elseif($product->images->count() > 0)
                                    <img 
                                        src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="font-medium text-sm text-gray-900 truncate">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="font-bold text-purple-600">{{ number_format($product->price, 0, ',', ' ') }} F</span>
                                    @if($product->sale_price)
                                        <span class="text-xs line-through text-gray-400">{{ number_format($product->sale_price, 0, ',', ' ') }} F</span>
                                    @endif
                                </div>
                                <div class="mt-2 text-xs">
                                    <span class="{{ $product->stock_quantity > 10 ? 'text-green-600' : ($product->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-600') }} font-medium">
                                        @if($product->stock_quantity > 0)
                                            {{ $product->stock_quantity }} en stock
                                        @else
                                            Rupture de stock
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-64 bg-white rounded-lg shadow-sm">
                    <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Aucun produit trouvé</p>
                    <p class="text-gray-400 text-sm mt-1">Essayez de modifier vos critères de recherche</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar - Cart -->
    <div class="w-full lg:w-2/5 xl:w-1/3 h-full bg-white border-l border-gray-200 flex flex-col">
        <!-- Cart Header -->
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Panier ({{ $cartCount }})</h2>
        </div>
        
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4">
            @if($cartCount > 0)
                <div class="space-y-4">
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-16 h-16 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                    @if($item->product->primaryImage)
                                        <img 
                                            src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                            alt="{{ $item->product->name }}" 
                                            class="w-full h-full object-cover"
                                        >
                                    @elseif($item->product->images->count() > 0)
                                        <img 
                                            src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                            alt="{{ $item->product->name }}" 
                                            class="w-full h-full object-cover"
                                        >
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-xs text-gray-500">
                                        @if($item->product_color_id && $item->productColor)
                                            {{ $item->productColor->name }}
                                        @endif
                                    </p>
                                    <p class="text-sm font-semibold text-purple-600 mt-1">
                                        {{ number_format(($item->product->price ?? $item->product->sale_price) * $item->quantity, 0, ',', ' ') }} F
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="decrementQty({{ $item->id }})"
                                    class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-md text-gray-600 hover:bg-gray-300"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="text-sm font-medium w-8 text-center">{{ $item->quantity }}</span>
                                <button 
                                    wire:click="incrementQty({{ $item->id }})"
                                    class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-md text-gray-600 hover:bg-gray-300"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="removeFromCart({{ $item->id }})"
                                    class="w-7 h-7 flex items-center justify-center bg-red-100 rounded-md text-red-600 hover:bg-red-200 ml-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-64">
                    <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Votre panier est vide</p>
                    <p class="text-gray-400 text-sm mt-1">Ajoutez des produits pour commencer</p>
                </div>
            @endif
        </div>
        
        <!-- Cart Footer -->
        <div class="border-t border-gray-200 p-4 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Sous-total</span>
                <span class="font-semibold">{{ number_format($totalPrice, 0, ',', ' ') }} F</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Taxes</span>
                <span class="font-semibold">0 F</span>
            </div>
            
            <div class="border-t pt-2">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Total</span>
                    <span>{{ number_format($totalPrice, 0, ',', ' ') }} F</span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button 
                    wire:click="clearCart"
                    wire:loading.attr="disabled"
                    class="cursor-pointer flex-1 py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                    {{ $cartCount == 0 ? 'disabled' : '' }}
                >
                    Vider le panier
                </button>
                <button 
                    wire:click="checkout"
                    wire:loading.attr="disabled"
                    class="cursor-pointer flex-1 py-2 px-4 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors font-medium"
                    {{ $cartCount == 0 ? 'disabled' : '' }}
                >
                    Finaliser la vente
                </button>
            </div>
        </div>
    </div>
    
    <!-- Product Detail Modal -->
    @if($showProductModal && $selectedProduct)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedProduct->name }}</h2>
                        <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Image -->
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            @if($selectedProduct->primaryImage)
                                <img 
                                    src="{{ asset('storage/' . $selectedProduct->primaryImage->image_path) }}" 
                                    alt="{{ $selectedProduct->name }}" 
                                    class="w-full h-64 object-cover"
                                >
                            @elseif($selectedProduct->images->count() > 0)
                                <img 
                                    src="{{ asset('storage/' . $selectedProduct->images->first()->image_path) }}" 
                                    alt="{{ $selectedProduct->name }}" 
                                    class="w-full h-64 object-cover"
                                >
                            @else
                                <div class="w-full h-64 flex items-center justify-center bg-gray-200 text-gray-400">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div>
                            <p class="text-gray-600 mb-2">{{ $selectedProduct->category->name }}</p>
                            
                            <div class="mb-4">
                                <span class="text-2xl font-bold text-purple-600">
                                    {{ number_format($selectedProduct->price, 0, ',', ' ') }} F
                                </span>
                                @if($selectedProduct->sale_price)
                                    <span class="ml-2 text-sm line-through text-gray-400">
                                        {{ number_format($selectedProduct->sale_price, 0, ',', ' ') }} F
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                {{ $selectedProduct->short_description ?? $selectedProduct->description }}
                            </p>
                            
                            <!-- Color Selection -->
                            @if($selectedProduct->colors->count() > 0)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($selectedProduct->colors as $color)
                                            <label class="flex items-center">
                                                <input 
                                                    type="radio" 
                                                    wire:model="productColorId"
                                                    value="{{ $color->id }}"
                                                    class="sr-only"
                                                >
                                                <span class="w-8 h-8 rounded-full border-2 {{ $productColorId == $color->id ? 'border-purple-500' : 'border-gray-300' }}" style="background-color: {{ $color->hex_code }}"></span>
                                                <span class="ml-1 text-xs">{{ $color->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Quantity Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                                <div class="flex items-center">
                                    <button 
                                        wire:click="decrementQty"
                                        class="w-10 h-10 flex items-center justify-center bg-gray-200 rounded-l-md text-gray-600 hover:bg-gray-300"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input 
                                        type="number" 
                                        wire:model="qtyCount"
                                        min="1" 
                                        class="w-16 h-10 text-center border-t border-b border-gray-300"
                                    >
                                    <button 
                                        wire:click="incrementQty"
                                        class="w-10 h-10 flex items-center justify-center bg-gray-200 rounded-r-md text-gray-600 hover:bg-gray-300"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button 
                                wire:click="addToCart"
                                class="w-full py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors font-medium"
                            >
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
