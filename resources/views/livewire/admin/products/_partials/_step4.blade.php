<!-- For product-edit -->
<!-- resources\views\livewire\admin\products\_partials\_step4.blade.php -->
<h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    SEO & Métadonnées
</h2>

<div class="space-y-8">
    <!-- Titre SEO -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
        <div class="flex items-center justify-between mb-4">
            <label class="block text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Titre SEO *
            </label>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">{{ strlen($meta_title ?? '') }}/60</span>
                <div class="w-16 h-2 bg-gray-200 rounded-full">
                    <div class="h-2 rounded-full transition-all duration-300 {{ strlen($meta_title ?? '') > 60 ? 'bg-red-500' : (strlen($meta_title ?? '') > 50 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                         style="width: {{ min((strlen($meta_title ?? '') / 60) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
        
        <input wire:model.live="meta_title" 
               type="text" 
               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
               placeholder="Titre optimisé pour les moteurs de recherche">
        
        <div class="mt-3 flex items-start space-x-3">
            <div class="flex-shrink-0 mt-1">
                @if(strlen($meta_title ?? '') >= 50 && strlen($meta_title ?? '') <= 60)
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @elseif(strlen($meta_title ?? '') > 60)
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
            <div class="text-sm">
                @if(strlen($meta_title ?? '') >= 50 && strlen($meta_title ?? '') <= 60)
                    <p class="text-green-700">✅ Longueur optimale pour le SEO</p>
                @elseif(strlen($meta_title ?? '') > 60)
                    <p class="text-red-700">❌ Trop long - Google tronquera le titre</p>
                @elseif(strlen($meta_title ?? '') > 30)
                    <p class="text-yellow-700">⚠️ Un peu court - Essayez 50-60 caractères</p>
                @else
                    <p class="text-gray-500">💡 Recommandé: 50-60 caractères pour un SEO optimal</p>
                @endif
            </div>
        </div>
        
        @error('meta_title')
            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Meta Description -->
    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
        <div class="flex items-center justify-between mb-4">
            <label class="block text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m-6-8h6M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Meta Description
            </label>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">{{ strlen($meta_description ?? '') }}/160</span>
                <div class="w-16 h-2 bg-gray-200 rounded-full">
                    <div class="h-2 rounded-full transition-all duration-300 {{ strlen($meta_description ?? '') > 160 ? 'bg-red-500' : (strlen($meta_description ?? '') > 150 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                         style="width: {{ min((strlen($meta_description ?? '') / 160) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
        
        <textarea wire:model.live="meta_description" 
                  rows="4"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm resize-none"
                  placeholder="Description concise et attractive qui apparaîtra dans les résultats de recherche Google. Décrivez les avantages et caractéristiques principales du produit."></textarea>
        
        <div class="mt-3 flex items-start space-x-3">
            <div class="flex-shrink-0 mt-1">
                @if(strlen($meta_description ?? '') >= 140 && strlen($meta_description ?? '') <= 160)
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @elseif(strlen($meta_description ?? '') > 160)
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
            <div class="text-sm">
                @if(strlen($meta_description ?? '') >= 140 && strlen($meta_description ?? '') <= 160)
                    <p class="text-green-700">✅ Longueur parfaite pour les résultats Google</p>
                @elseif(strlen($meta_description ?? '') > 160)
                    <p class="text-red-700">❌ Trop long - Google tronquera la description</p>
                @elseif(strlen($meta_description ?? '') > 100)
                    <p class="text-yellow-700">⚠️ Correct mais pourrait être plus long (140-160 caractères)</p>
                @else
                    <p class="text-gray-500">💡 Recommandé: 140-160 caractères pour une description optimale</p>
                @endif
            </div>
        </div>
        
        @error('meta_description')
            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Mots-clés SEO -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
        <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Mots-clés SEO
        </label>
        
        <input wire:model="meta_keywords" 
               type="text" 
               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm"
               placeholder="smartphone, apple, iphone, technologie, mobile, 5G">
        
        <div class="mt-3 bg-white/60 rounded-lg p-4 border border-purple-200">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-gray-700">
                    <p class="font-medium mb-2">💡 Conseils pour les mots-clés :</p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Séparez les mots-clés par des virgules</li>
                        <li>Utilisez 5-10 mots-clés pertinents maximum</li>
                        <li>Incluez des variations et synonymes</li>
                        <li>Pensez aux termes que vos clients utiliseraient</li>
                    </ul>
                </div>
            </div>
        </div>
        
        @error('meta_keywords')
            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Prévisualisation Google -->
    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Prévisualisation Google
        </h3>
        
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="max-w-2xl">
                <!-- URL -->
                <div class="text-sm text-green-700 mb-1">
                    https://votre-site.com/produits/{{ $slug ?: 'nom-du-produit' }}
                </div>
                
                <!-- Title -->
                <h3 class="text-xl text-blue-600 hover:underline cursor-pointer mb-2 leading-tight">
                    {{ $meta_title ?: ($name ?: 'Titre du produit') }}
                    @if(strlen($meta_title ?? '') > 60)
                        <span class="text-gray-500">...</span>
                    @endif
                </h3>
                
                <!-- Description -->
                <p class="text-sm text-gray-600 leading-relaxed">
                    @if($meta_description)
                        {{ strlen($meta_description) > 160 ? substr($meta_description, 0, 160) . '...' : $meta_description }}
                    @else
                        <span class="text-gray-400 italic">La meta description apparaîtra ici...</span>
                    @endif
                </p>
                
                <!-- Additional info -->
                <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                    @if($price ?? false)
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            À partir de {{ number_format($price, 2) }}€
                        </span>
                    @endif
                    @if($brand ?? false)
                        <span>{{ $brand }}</span>
                    @endif
                    <span>⭐⭐⭐⭐⭐ Avis clients</span>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p class="flex items-center">
                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ceci est un aperçu de l'apparence de votre produit dans les résultats de recherche Google.
            </p>
        </div>
    </div>

    <!-- Score SEO -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Score SEO
        </h3>
        
        @php
            $seoScore = 0;
            $checks = [];
            
            // Vérification titre
            if(strlen($meta_title ?? '') >= 50 && strlen($meta_title ?? '') <= 60) {
                $seoScore += 30;
                $checks[] = ['status' => 'good', 'text' => 'Titre SEO optimisé'];
            } elseif(strlen($meta_title ?? '') > 0) {
                $seoScore += 15;
                $checks[] = ['status' => 'warning', 'text' => 'Titre SEO présent mais à améliorer'];
            } else {
                $checks[] = ['status' => 'error', 'text' => 'Titre SEO manquant'];
            }
            
            // Vérification description
            if(strlen($meta_description ?? '') >= 140 && strlen($meta_description ?? '') <= 160) {
                $seoScore += 25;
                $checks[] = ['status' => 'good', 'text' => 'Meta description optimisée'];
            } elseif(strlen($meta_description ?? '') > 0) {
                $seoScore += 12;
                $checks[] = ['status' => 'warning', 'text' => 'Meta description présente mais à améliorer'];
            } else {
                $checks[] = ['status' => 'error', 'text' => 'Meta description manquante'];
            }
            
            // Vérification mots-clés
            if(strlen($meta_keywords ?? '') > 0) {
                $keywordCount = count(array_filter(explode(',', $meta_keywords ?? ''), 'trim'));
                if($keywordCount >= 3 && $keywordCount <= 10) {
                    $seoScore += 20;
                    $checks[] = ['status' => 'good', 'text' => "Mots-clés bien définis ({$keywordCount})"];
                } else {
                    $seoScore += 10;
                    $checks[] = ['status' => 'warning', 'text' => "Mots-clés présents ({$keywordCount}) mais à optimiser"];
                }
            } else {
                $checks[] = ['status' => 'error', 'text' => 'Mots-clés manquants'];
            }
            
            // Vérifications produit (depuis le composant parent)
            if(strlen($name ?? '') > 0) {
                $seoScore += 10;
                $checks[] = ['status' => 'good', 'text' => 'Nom du produit défini'];
            }
            
            if(strlen($description ?? '') > 100) {
                $seoScore += 10;
                $checks[] = ['status' => 'good', 'text' => 'Description détaillée'];
            }
            
            if(!empty($categories ?? [])) {
                $seoScore += 5;
                $checks[] = ['status' => 'good', 'text' => 'Catégories définies'];
            }
        @endphp
        
        <div class="flex items-center mb-6">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Score SEO</span>
                    <span class="text-2xl font-bold {{ $seoScore >= 80 ? 'text-green-600' : ($seoScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $seoScore }}/100
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $seoScore >= 80 ? 'bg-green-500' : ($seoScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                         style="width: {{ $seoScore }}%"></div>
                </div>
            </div>
            <div class="ml-4">
                @if($seoScore >= 80)
                    <div class="text-green-600 text-center">
                        <svg class="w-8 h-8 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">Excellent</span>
                    </div>
                @elseif($seoScore >= 60)
                    <div class="text-yellow-600 text-center">
                        <svg class="w-8 h-8 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">Correct</span>
                    </div>
                @else
                    <div class="text-red-600 text-center">
                        <svg class="w-8 h-8 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">À améliorer</span>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="space-y-2">
            @foreach($checks as $check)
                <div class="flex items-center space-x-3 text-sm">
                    @if($check['status'] === 'good')
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-700">{{ $check['text'] }}</span>
                    @elseif($check['status'] === 'warning')
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-yellow-700">{{ $check['text'] }}</span>
                    @else
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-700">{{ $check['text'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>