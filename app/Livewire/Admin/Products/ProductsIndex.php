<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

//#[Layout('layouts.admin')]
#[Title('Gestion des Produits')]
class ProductsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 12;
    public $selectedProducts = [];
    public $selectAll = false;
    public $viewMode = 'grid'; // Nouvelle variable pour le mode d'affichage

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'viewMode' => ['except' => 'grid'], // Ajout au query string
    ];

    public function mount()
    {
        // Récupérer le mode d'affichage depuis le localStorage ou utiliser la valeur par défaut
        if (request()->has('viewMode')) {
            $this->viewMode = request('viewMode');
        } elseif (isset($_COOKIE['products_view_mode'])) {
            $this->viewMode = $_COOKIE['products_view_mode'];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedBrandFilter()
    {
        $this->resetPage();
    }

    public function updatedViewMode($value)
    {
        // Sauvegarder la préférence dans un cookie
        setcookie('products_view_mode', $value, time() + (30 * 24 * 60 * 60), '/');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedProducts = $this->products->pluck('id')->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->updatedViewMode($mode);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleFeatured($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_featured' => !$product->is_featured]);

        $this->dispatch('product-updated', [
            'message' => 'Statut vedette mis à jour avec succès!'
        ]);
    }

    public function toggleStatus($productId)
    {
        $product = Product::findOrFail($productId);
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        $this->dispatch('product-updated', [
            'message' => 'Statut du produit mis à jour!'
        ]);
    }

    public function deleteProduct($productId)
    {
        //$product = Product::findOrFail($productId);
        $product = Product::with(['images', 'colors'])->findOrFail($productId);
        
        // Supprimer les images physiques
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        // Supprimer les associations de couleurs
        $product->colors()->detach();
        
        // Supprimer le produit
        $product->delete();

        $this->dispatch('product-deleted', [
            'message' => 'Produit supprimé avec succès!'
        ]);
    }

    public function bulkDelete()
    {
        if (empty($this->selectedProducts)) {
            return;
        }

        Product::whereIn('id', $this->selectedProducts)->delete();
        $this->selectedProducts = [];
        $this->selectAll = false;

        $this->dispatch('products-bulk-deleted', [
            'message' => 'Produits sélectionnés supprimés!'
        ]);
    }

    public function bulkUpdateStatus($status)
    {
        if (empty($this->selectedProducts)) {
            return;
        }

        Product::whereIn('id', $this->selectedProducts)->update(['status' => $status]);
        $this->selectedProducts = [];
        $this->selectAll = false;

        $this->dispatch('products-bulk-updated', [
            'message' => 'Statut des produits mis à jour!'
        ]);
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->with(['category', 'brand', 'primaryImage'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->brandFilter, function ($query) {
                $query->where('brand_id', $this->brandFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.products.products-index', [
            'products' => $this->products,
            'categories' => Category::active()->ordered()->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }
}
