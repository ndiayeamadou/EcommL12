<!-- resources\views\livewire\admin\category-manager.blade.php -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header avec animations -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="animate-pulse-slow">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 animate-fade-in">
                            Gestion des Catégories
                        </h1>
                        <p class="text-gray-600 animate-slide-up-delay">
                            {{ $categories->total() }} catégories au total
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button wire:click="create" 
                       class="cursor-pointer group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Nouvelle Catégorie</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche avec animations -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 animate-slide-up">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <div class="relative group">
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               placeholder="Rechercher par nom ou description..."
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtre Statut -->
                <div>
                    <select wire:model.live="filterStatus"
                            class="w-full py-3 px-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>

                <!-- Filtre Type -->
                <div>
                    <select wire:model.live="filterParent"
                            class="w-full py-3 px-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 bg-white/90 backdrop-blur-sm">
                        <option value="all">Tous les types</option>
                        <option value="parent">Catégories parentes</option>
                        <option value="child">Sous-catégories</option>
                    </select>
                </div>
            </div>

            <!-- Actions en lot -->
            @if(count($selectedCategories) > 0)
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200 animate-slide-down">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-800 font-medium">
                            {{ count($selectedCategories) }} catégorie(s) sélectionnée(s)
                        </span>
                        @can('manage_system_users')
                        <div class="flex items-center space-x-2">
                            <button wire:click="bulkDelete"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ces catégories ?')"
                                    class="cursor-pointer bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                Supprimer
                            </button>
                        </div>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Liste des catégories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $index => $category)
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-white/50 animate-fade-in-up"
                     style="animation-delay: {{ $index * 0.1 }}s">
                    
                    <!-- Image de la catégorie -->
                    <div class="relative h-48 overflow-hidden rounded-t-2xl">
                        @if($category->image)
                            <img src="{{ asset($category->image_url) }}" 
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background-color: {{ $category->color }}20">
                                @if($category->icon)
                                    <span class="text-4xl" style="color: {{ $category->color }}">{{ $category->icon }}</span>
                                @else
                                    <svg class="w-12 h-12" style="color: {{ $category->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Overlay avec actions -->
                        @can('manage_system_users')
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center space-x-2">
                            <button wire:click="edit({{ $category->id }})"
                               class="cursor-pointer bg-white/90 hover:bg-white text-gray-800 p-2 rounded-lg transition-all duration-200 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $category->id }})"
                                    class="cursor-pointer bg-white/90 hover:bg-white text-gray-800 p-2 rounded-lg transition-all duration-200 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endcan

                        <!-- Checkbox de sélection -->
                        <div class="absolute top-3 right-3">
                            <input type="checkbox" 
                                   wire:model.live="selectedCategories" 
                                   value="{{ $category->id }}"
                                   class="cursor-pointer w-5 h-5 text-blue-600 rounded border-2 border-white/80 focus:ring-blue-500 transition-all">
                        </div>

                        <!-- Badge de couleur -->
                        <div class="absolute top-3 left-3">
                            <div class="w-6 h-6 rounded-full border-2 border-white/80 shadow-sm" style="background-color: {{ $category->color }}"></div>
                        </div>
                    </div>
                    
                    <!-- Contenu de la carte -->
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $category->parent ? $category->parent->name : 'Catégorie principale' }}</p>
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            {{ $category->description ?: 'Aucune description' }}
                        </p>

                        <!-- Informations complémentaires -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>{{ $category->products_count }} produit(s)</span>
                            <span>{{ $category->children_count }} sous-catégorie(s)</span>
                        </div>

                        <!-- Statut et actions -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button wire:click="toggleStatus({{ $category->id }})"
                                        class="px-3 py-1 rounded-full text-xs font-semibold transition-all duration-200 {{ $category->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </button>
                                
                                @if($category->is_featured)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-semibold">
                                        Vedette
                                    </span>
                                @endif
                            </div>
                            
                            @can('manage_system_users')
                            <div class="flex items-center space-x-1">
                                <button wire:click="edit({{ $category->id }})"
                                   class="cursor-pointer p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $category->id }})"
                                        class="cursor-pointer p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 animate-fade-in">
                    <div class="max-w-md mx-auto">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune catégorie trouvée</h3>
                        <p class="text-gray-600 mb-6">Aucune catégorie ne correspond à vos critères de recherche.</p>
                        <button wire:click="create" 
                           class="cursor-pointer inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Créer la première catégorie
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="mt-8 flex justify-center animate-fade-in-up">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-4">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de création/édition -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto animate-slide-up">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $editMode ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                    </h2>
                </div>
                
                <form wire:submit="save" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" wire:model="name" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Slug -->
                        <!-- Display Slug Field Only In Edit Mode - Optional -->
                        {{-- @if($editMode)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                            <input type="text" wire:model="slug" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        @endif --}}
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Catégorie parente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie parente</label>
                            <select wire:model="parent_id" 
                                    class="cursor-pointer w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                                <option value="">Aucune (catégorie principale)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Ordre de tri -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                            <input type="number" wire:model="sort_order" min="0" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            @error('sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Couleur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" wire:model="color" 
                                       class="w-12 h-12 rounded-lg border border-gray-200 cursor-pointer">
                                <input type="text" wire:model="color" 
                                       class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            </div>
                            @error('color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Icône -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                            <input type="text" wire:model="icon" placeholder="Ex: 🚀, 📱, 👕"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Image -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            @if($image)
                                <div class="mb-4">
                                    <img src="{{ $image->temporaryUrl() }}" class="h-32 rounded-lg object-cover">
                                </div>
                            @elseif($editMode && $image)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/categories/' . $image) }}" class="h-32 rounded-lg object-cover">
                                </div>
                            @endif
                            <input type="file" wire:model="image" 
                                   class="cursor-pointer w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Statut et vedette -->
                        <div class="flex items-center space-x-4 md:col-span-2 pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Catégorie active</span>
                            </label>
                            
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Catégorie vedette</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" 
                                class="cursor-pointer px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="submit"
                                class="cursor-pointer px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                            {{ $editMode ? 'Modifier' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal de confirmation de suppression -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 animate-slide-up">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Confirmer la suppression</h2>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.</p>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showDeleteModal', false)" 
                                class="cursor-pointer px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="button" wire:click="delete"
                                class="cursor-pointer px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
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
    Livewire.on('category-created', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('category-updated', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('category-deleted', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('categories-deleted', (data) => {
        showToast(data.message, 'success');
    });

    Livewire.on('error', (data) => {
        showToast(data.message, 'error');
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
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
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>
@endpush