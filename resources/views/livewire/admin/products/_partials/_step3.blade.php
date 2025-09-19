<!-- For product-edit -->
<!-- resources\views\livewire\admin\products\_partials\_step3.blade.php -->
<h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
    </svg>
    Catégories, Tags & Attributs
</h2>

<div class="space-y-8">
    <!-- Catégories -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
        <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Catégories
        </label>
        
        <div class="flex space-x-2 mb-4">
            <input wire:model="categoryInput" 
                   wire:keydown.enter.prevent="addCategory"
                   type="text" 
                   class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-white/70"
                   placeholder="Ajouter une catégorie (ex: Électronique, Mode...)">
            <button type="button" 
                    wire:click="addCategory"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Ajouter</span>
            </button>
        </div>
        
        @if(count($categories) > 0)
            <div class="flex flex-wrap gap-2 animate-slide-down">
                @foreach($categories as $index => $category)
                    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-2 rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $category }}
                        <button type="button" 
                                wire:click="removeCategory({{ $index }})"
                                class="ml-2 text-blue-600 hover:text-blue-800 hover:bg-blue-200 rounded-full p-1 transition-all duration-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-gray-500 bg-white/50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-sm">Aucune catégorie ajoutée</p>
            </div>
        @endif
    </div>

    <!-- Tags -->
    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
        <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Tags & Mots-clés
        </label>
        
        <div class="flex space-x-2 mb-4">
            <input wire:model="tagInput" 
                   wire:keydown.enter.prevent="addTag"
                   type="text" 
                   class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all bg-white/70"
                   placeholder="Ajouter un tag (ex: nouveau, promo, bestseller...)">
            <button type="button" 
                    wire:click="addTag"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Ajouter</span>
            </button>
        </div>
        
        @if(count($tags) > 0)
            <div class="flex flex-wrap gap-2 animate-slide-down">
                @foreach($tags as $index => $tag)
                    <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-2 rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $tag }}
                        <button type="button" 
                                wire:click="removeTag({{ $index }})"
                                class="ml-2 text-green-600 hover:text-green-800 hover:bg-green-200 rounded-full p-1 transition-all duration-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-gray-500 bg-white/50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-sm">Aucun tag ajouté</p>
            </div>
        @endif
    </div>

    <!-- Propriétés physiques -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4a1 1 0 011-1h4M4 8v8a1 1 0 001 1h8m-9-9h16a1 1 0 011 1v8a1 1 0 01-1 1h-8m-7 0V8a1 1 0 011-1h4m-5 9v4a1 1 0 001 1h4"/>
            </svg>
            Propriétés Physiques
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white/70 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-3m-3 3l-3-3"/>
                    </svg>
                    Poids (kg)
                </label>
                <input wire:model="weight" 
                       type="number" 
                       step="0.01"
                       min="0"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all bg-white"
                       placeholder="0.5">
                @error('weight')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="bg-white/70 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Longueur (cm)
                </label>
                <input wire:model="dimensions.length" 
                       type="number" 
                       step="0.1"
                       min="0"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all bg-white"
                       placeholder="15.0">
            </div>
            
            <div class="bg-white/70 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Largeur (cm)
                </label>
                <input wire:model="dimensions.width" 
                       type="number" 
                       step="0.1"
                       min="0"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all bg-white"
                       placeholder="7.5">
            </div>
            
            <div class="bg-white/70 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Hauteur (cm)
                </label>
                <input wire:model="dimensions.height" 
                       type="number" 
                       step="0.1"
                       min="0"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all bg-white"
                       placeholder="0.8">
            </div>
        </div>
    </div>

    <!-- Attributs personnalisés -->
    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m-6-8h6M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Attributs du Produit
            </h3>
            <button type="button" 
                    wire:click="addAttribute"
                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Nouvel Attribut</span>
            </button>
        </div>
        
        @if(count($productAttributes) > 0)
            <div class="space-y-6">
                @foreach($productAttributes as $attrIndex => $attribute)
                    <div class="bg-white/70 rounded-lg p-6 border border-orange-200 shadow-sm animate-slide-down">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="bg-orange-100 p-2 rounded-lg">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <input wire:model="productAttributes.{{ $attrIndex }}.name" 
                                       type="text" 
                                       class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all font-medium"
                                       placeholder="Nom de l'attribut (ex: Couleur, Taille, Matériau...)">
                            </div>
                            <button type="button" 
                                    wire:click="removeAttribute({{ $attrIndex }})"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex space-x-2 mb-4">
                            <input wire:model="productAttributes.{{ $attrIndex }}.value_input" 
                                   wire:keydown.enter.prevent="addAttributeValue({{ $attrIndex }})"
                                   type="text" 
                                   class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all bg-white"
                                   placeholder="Ajouter une valeur (ex: Rouge, Bleu, XL...)">
                            <button type="button" 
                                    wire:click="addAttributeValue({{ $attrIndex }})"
                                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm transition-all duration-200">
                                Ajouter
                            </button>
                        </div>
                        
                        @if(isset($attribute['values']) && count($attribute['values']) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($attribute['values'] as $valueIndex => $value)
                                    <span class="inline-flex items-center bg-orange-100 border border-orange-200 px-3 py-2 rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in">
                                        <span class="w-2 h-2 bg-orange-400 rounded-full mr-2"></span>
                                        {{ $value }}
                                        <button type="button" 
                                                wire:click="removeAttributeValue({{ $attrIndex }}, {{ $valueIndex }})"
                                                class="ml-2 text-orange-600 hover:text-red-600 hover:bg-red-50 rounded-full p-1 transition-all duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <p class="text-sm">Aucune valeur ajoutée pour cet attribut</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 bg-white/50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m-6-8h6M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Aucun attribut défini</h4>
                <p class="text-sm text-gray-500 mb-4">Les attributs permettent de définir les variations de votre produit (couleur, taille, etc.)</p>
                <button type="button" 
                        wire:click="addAttribute"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Créer le premier attribut
                </button>
            </div>
        @endif
    </div>
</div>