<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryManager extends Component
{
    use WithFileUploads, WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;

    // Form fields
    public $categoryId;
    public $name;
    public $slug;
    public $description;
    public $image;
    public $icon;
    public $color = '#6366f1';
    public $parent_id;
    public $sort_order = 0;
    public $is_active = true;
    public $is_featured = false;

    // Filters
    public $search = '';
    public $filterStatus = 'all';
    public $filterParent = 'all';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';

    // UI states
    public $selectedCategories = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
        'filterParent' => ['except' => 'all']
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->categoryId)
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ];
    }

    public function mount()
    {
        $this->resetFilters();
    }

    public function updatedName($value)
    {
        if (!$this->editMode) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedCategories = $this->categories->pluck('id')->toArray();
        } else {
            $this->selectedCategories = [];
        }
    }

    public function getCategoriesProperty()
    {
        return Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== 'all', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->when($this->filterParent !== 'all', function ($query) {
                if ($this->filterParent === 'parent') {
                    $query->whereNull('parent_id');
                } else {
                    $query->whereNotNull('parent_id');
                }
            })
            ->with('parent', 'children')
            ->withCount('products')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function getParentCategoriesProperty()
    {
        return Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->parent_id = $category->parent_id;
        $this->sort_order = $category->sort_order;
        $this->is_active = $category->is_active;
        $this->is_featured = $category->is_featured;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'parent_id' => $this->parent_id ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured
        ];

        // Gestion professionnelle de l'upload d'image
        if ($this->image) {
            // En mode édition, supprimer l'ancienne image si elle existe
            if ($this->editMode) {
                $oldCategory = Category::find($this->categoryId);
                if ($oldCategory->image) {
                    Storage::disk('public')->delete($oldCategory->image);
                }
            }
            
            // Stocker la nouvelle image
            $data['image'] = $this->image->store('categories', 'public');
        }

        if ($this->editMode) {
            $category = Category::find($this->categoryId);
            $category->update($data);
            $this->dispatch('category-updated', message: 'Catégorie mise à jour avec succès!');
        } else {
            Category::create($data);
            $this->dispatch('category-created', message: 'Catégorie créée avec succès!');
        }

        $this->closeModal();
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $category = Category::findOrFail($this->categoryId);
        
        // Check if category has children or products
        if ($category->children()->exists()) {
            $this->dispatch('error', message: 'Impossible de supprimer une catégorie qui a des sous-catégories.');
            return;
        }
        
        if ($category->products()->exists()) {
            $this->dispatch('error', message: 'Impossible de supprimer une catégorie qui contient des produits.');
            return;
        }
        
        $category->delete();
        $this->dispatch('category-deleted', message: 'Catégorie supprimée avec succès!');
        $this->showDeleteModal = false;
    }

    public function bulkDelete()
    {
        if (empty($this->selectedCategories)) {
            $this->dispatch('error', message: 'Aucune catégorie sélectionnée.');
            return;
        }

        $categories = Category::whereIn('id', $this->selectedCategories)->get();
        
        foreach ($categories as $category) {
            if (!$category->children()->exists() && !$category->products()->exists()) {
                $category->delete();
            }
        }
        
        $this->selectedCategories = [];
        $this->selectAll = false;
        $this->dispatch('categories-deleted', message: 'Catégories sélectionnées supprimées avec succès!');
    }

    public function toggleStatus($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'activée' : 'désactivée';
        $this->dispatch('category-updated', message: "Catégorie {$status} avec succès!");
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->filterParent = 'all';
        $this->sortBy = 'sort_order';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'categoryId', 'name', 'slug', 'description', 'image', 
            'icon', 'color', 'parent_id', 'sort_order', 'is_active', 'is_featured'
        ]);
        
        $this->color = '#6366f1';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->is_featured = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => $this->categories,
            'parentCategories' => $this->parentCategories
        ]);
    }
}
