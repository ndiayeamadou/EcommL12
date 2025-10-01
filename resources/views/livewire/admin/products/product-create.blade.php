<!-- resources/views/livewire/admin/products/product-create.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.products.index') }}" 
                       class="p-2 rounded-lg bg-white/60 hover:bg-white/80 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 animate-fade-in">
                            Créer un Nouveau Produit
                        </h1>
                        <p class="text-gray-600 animate-slide-up-delay">
                            Étape {{ $currentStep }} sur {{ $totalSteps }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-slide-up">
            <nav class="flex justify-between">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <button wire:click="goToStep({{ $i }})" 
                            class="flex items-center space-x-2 {{ $i <= $currentStep ? 'text-blue-600' : 'text-gray-400' }} transition-all duration-300">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $i <= $currentStep ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }} transition-all duration-300">
                            @if($i < $currentStep)
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <span class="text-sm font-semibold">{{ $i }}</span>
                            @endif
                        </div>
                        <span class="hidden sm:block text-sm font-medium">
                            @switch($i)
                                @case(1) Informations de Base @break
                                @case(2) Images & Stocks @break
                                @case(3) Attributs @break
                                @case(4) SEO & Métadonnées @break
                            @endswitch
                        </span>
                    </button>
                @endfor
            </nav>
            
            <!-- Progress bar -->
            <div class="mt-6 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <form wire:submit="save">
            <!-- Step 1: Informations de Base -->
            @if($currentStep === 1)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-8 animate-fade-in-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informations de Base
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Nom du produit -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom du Produit <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.blur="name" 
                                   type="text" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="Ex: iPhone 15 Pro Max">
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="category_id" 
                                    class="cursor-pointer w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Marque -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Marque
                            </label>
                            <select wire:model="brand_id" 
                                    class="cursor-pointer w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                                <option value="">Sélectionner une marque</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Slug (URL)
                            </label>
                            <input wire:model="slug" 
                                   type="text" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="iphone-15-pro-max">
                            @error('slug')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                SKU (Code Produit)
                            </label>
                            <input wire:model="sku" 
                                   type="text" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                            @error('sku')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prix -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prix Regular (F) <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.blur="price" 
                                   type="number" 
                                   step="0.01" 
                                   min="0"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="999.00">
                            @error('price')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prix de Vente -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prix de Vente (F)
                            </label>
                            <input wire:model="sale_price" 
                                   type="number" 
                                   step="0.01" 
                                   min="0"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="799.00">
                            @error('sale_price')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Quantité en Stock <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="stock_quantity" 
                                   type="number" 
                                   min="0"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                            @error('stock_quantity')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Statut
                            </label>
                            <select wire:model="status" 
                                    class="cursor-pointer w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                                <option value="draft">Brouillon</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description courte -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description Courte
                            </label>
                            <textarea wire:model="short_description" 
                                      rows="3"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                      placeholder="Une description courte et accrocheuse du produit..."></textarea>
                            @error('short_description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description complète -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description Complète
                            </label>
                            <textarea wire:model="description" 
                                      rows="6"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                      placeholder="Description détaillée du produit..."></textarea>
                            @error('description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input wire:model="manage_stock" 
                                           type="checkbox" 
                                           id="manage_stock"
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-all">
                                    <label for="manage_stock" class="ml-3 text-sm font-medium text-gray-700">
                                        Gérer le stock
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input wire:model="in_stock" 
                                           type="checkbox" 
                                           id="in_stock"
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-all">
                                    <label for="in_stock" class="ml-3 text-sm font-medium text-gray-700">
                                        En stock
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input wire:model="is_featured" 
                                           type="checkbox" 
                                           id="is_featured"
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-all">
                                    <label for="is_featured" class="ml-3 text-sm font-medium text-gray-700">
                                        Produit vedette
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 2: Images & Stocks -->
            @if($currentStep === 2)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-8 animate-fade-in-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Images & Stocks
                    </h2>

                    <div class="space-y-8">
                        <!-- Images principales -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                Images Principales (Max 10)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 transition-colors rounded-xl p-8 text-center">
                                <input wire:model="images" 
                                       type="file" 
                                       multiple 
                                       accept="image/*"
                                       class="hidden" 
                                       id="images">
                                <label for="images" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-gray-600 font-medium">Cliquez pour sélectionner les images</p>
                                    <p class="text-gray-400 text-sm mt-2">PNG, JPG, JPEG jusqu'à 10MB chacune</p>
                                </label>
                            </div>
                            
                            <!-- Prévisualisation des images -->
                            @if(count($images) > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                    @foreach($images as $index => $image)
                                        <div class="relative group">
                                            <img src="{{ $image->temporaryUrl() }}" 
                                                 class="w-full h-32 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow cursor-pointer"
                                                 wire:click="setPrimaryImage({{ $index }})">
                                            <div class="absolute top-2 left-2">
                                                @if($index === $primaryImageIndex)
                                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                                        Principale
                                                    </span>
                                                @endif
                                            </div>
                                            <button type="button" 
                                                    wire:click="removeImage({{ $index }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Cliquez sur une image pour la définir comme image principale</p>
                            @endif
                            
                            @error('images')
                                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stocks par couleur -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stocks par Couleur</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($availableColors as $color)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-6 h-6 rounded-full border border-gray-300" style="background-color: {{ $color->hex_code }}"></div>
                                            <span class="font-medium">{{ $color->name }}</span>
                                        </div>
                                        <input wire:model="colorStocks.{{ $color->id }}"
                                               type="number" 
                                               min="0"
                                               class="w-20 px-3 py-1 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                               placeholder="0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 3: Attributs -->
            @if($currentStep === 3)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-8 animate-fade-in-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Attributs & Tags
                    </h2>

                    <div class="space-y-8">
                        <!-- Tags -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                Tags
                            </label>
                            <div class="flex space-x-2 mb-4">
                                <input wire:model="tagInput" 
                                       wire:keydown.enter.prevent="addTag"
                                       type="text" 
                                       class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                       placeholder="Ajouter un tag...">
                                <button type="button" 
                                        wire:click="addTag"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                    Ajouter
                                </button>
                            </div>
                            
                            @if(count($tags) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tags as $index => $tag)
                                        <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                            {{ $tag }}
                                            <button type="button" 
                                                    wire:click="removeTag({{ $index }})"
                                                    class="ml-2 text-green-600 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Propriétés physiques -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Propriétés Physiques</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Poids (kg)</label>
                                    <input wire:model="weight" 
                                           type="number" 
                                           step="0.01"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                           placeholder="0.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Longueur (cm)</label>
                                    <input wire:model="dimensions.length" 
                                           type="number" 
                                           step="0.1"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                           placeholder="15.0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Largeur (cm)</label>
                                    <input wire:model="dimensions.width" 
                                           type="number" 
                                           step="0.1"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                           placeholder="7.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hauteur (cm)</label>
                                    <input wire:model="dimensions.height" 
                                           type="number" 
                                           step="0.1"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                           placeholder="0.8">
                                </div>
                            </div>
                        </div>

                        <!-- Attributs personnalisés -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Attributs du Produit</h3>
                                <button type="button" 
                                        wire:click="addAttribute"
                                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    + Ajouter Attribut
                                </button>
                            </div>
                            
                            @if(count($productAttributes) > 0)
                                <div class="space-y-4">
                                    @foreach($productAttributes as $attrIndex => $attribute)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center justify-between mb-4">
                                                <input wire:model="productAttributes.{{ $attrIndex }}.name" 
                                                       type="text" 
                                                       class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all mr-4"
                                                       placeholder="Nom de l'attribut (ex: Couleur, Taille...)">
                                                <button type="button" 
                                                        wire:click="removeAttribute({{ $attrIndex }})"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="flex space-x-2 mb-3">
                                                <input wire:model="productAttributes.{{ $attrIndex }}.value_input" 
                                                       wire:keydown.enter.prevent="addAttributeValue({{ $attrIndex }})"
                                                       type="text" 
                                                       class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                                       placeholder="Ajouter une valeur...">
                                                <button type="button" 
                                                        wire:click="addAttributeValue({{ $attrIndex }})"
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                                                    Ajouter
                                                </button>
                                            </div>
                                            
                                            @if(isset($attribute['values']) && count($attribute['values']) > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($attribute['values'] as $valueIndex => $value)
                                                        <span class="inline-flex items-center bg-white border border-gray-200 px-3 py-1 rounded-full text-sm">
                                                            {{ $value }}
                                                            <button type="button" 
                                                                    wire:click="removeAttributeValue({{ $attrIndex }}, {{ $valueIndex }})"
                                                                    class="ml-2 text-gray-400 hover:text-red-600">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 4: SEO & Métadonnées -->
            @if($currentStep === 4)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-8 animate-fade-in-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        SEO & Métadonnées
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Titre SEO
                            </label>
                            <input wire:model="meta_title" 
                                   type="text" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="Titre optimisé pour les moteurs de recherche">
                            <p class="text-sm text-gray-500 mt-1">Recommandé: 50-60 caractères</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea wire:model="meta_description" 
                                      rows="3"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                      placeholder="Description pour les résultats de recherche..."></textarea>
                            <p class="text-sm text-gray-500 mt-1">Recommandé: 150-160 caractères</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Mots-clés SEO
                            </label>
                            <input wire:model="meta_keywords" 
                                   type="text" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                   placeholder="smartphone, apple, iphone, technologie">
                            <p class="text-sm text-gray-500 mt-1">Séparez les mots-clés par des virgules</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" 
                        wire:click="previousStep"
                        @if($currentStep === 1) disabled @endif
                        class="cursor-pointer px-6 py-3 bg-gray-300 hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed text-gray-700 font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 disabled:transform-none">
                    ← Précédent
                </button>
                
                @if($currentStep < $totalSteps)
                    <button type="button" 
                            wire:click="nextStep"
                            class="cursor-pointer px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                        Suivant →
                    </button>
                @else
                    <button type="submit" 
                            class="cursor-pointer px-8 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                        Créer le Produit ✓
                    </button>
                @endif
            </div>
        </form>
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

        .animate-fade-in { animation: fade-in 0.8s ease-out; }
        .animate-slide-up { animation: slide-up 0.6s ease-out; }
        .animate-slide-up-delay { animation: slide-up 0.8s ease-out; }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out; }
    </style>
</div>