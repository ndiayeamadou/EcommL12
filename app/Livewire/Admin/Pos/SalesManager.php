<?php

namespace App\Livewire\Admin\Pos;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SalesManager extends Component
{
    use WithPagination;
    
    // List filters and sorting
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $perPage = 12;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'perPage' => ['except' => 12],
    ];

    // Cart properties
    public $cart, $totalPrice = 0;
    public $product, $category, $productColorSelectedQty, $productColorId, $qtyCount = 1, $colorName;
    public $cartCount;
    public $selectedProduct = null;
    public $showProductModal = false;

    public function mount()
    {
        $this->checkCartCount();
    }

    #[On('cart-updated')]
    public function checkCartCount()
    {
        if (Auth::check()) {
            $this->cartCount = Cart::where('user_id', auth()->user()->id)->count();
            $this->cart = Cart::where('user_id', auth()->user()->id)->get();
            $this->calculateTotal();
        } else {
            $this->cartCount = 0;
            $this->cart = collect();
        }
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $this->totalPrice += $price * $item->quantity;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter() 
    {
        $this->resetPage();
    }
    
    public function updatingPerPage()
    {
        $this->resetPage();
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

    public function showProductDetails($productId)
    {
        $this->selectedProduct = Product::with(['category', 'brand', 'images', 'colors'])->find($productId);
        $this->showProductModal = true;
        $this->qtyCount = 1;
        $this->productColorId = null;
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
        $this->qtyCount = 1;
        $this->productColorId = null;
    }
    
    public function addToCart($productId = null)
    {
        $productId = $productId ?? $this->selectedProduct->id;
        
        if (Auth::check()) {
            $product = Product::where('id', $productId)->where('status', 'active')->first();
            
            if ($product) {
                // Check if product has colors
                if ($product->colors()->count() > 0) {
                    if ($this->productColorId != null) {
                        // Check if product with this color already exists in cart
                        $existingCartItem = Cart::where('user_id', auth()->user()->id)
                            ->where('product_id', $productId)
                            ->where('product_color_id', $this->productColorId)
                            ->first();
                            
                        if ($existingCartItem) {
                            $this->dispatch('notify', [
                                'text' => 'Ce produit avec cette couleur figure déjà dans le panier.',
                                'type' => 'warning',
                                'status' => ''
                            ]);
                            return;
                        }
                        
                        // Get the product color
                        $productColor = $product->colors()->where('color_id', $this->productColorId)->first();
                        
                        if ($productColor && $productColor->pivot->quantity > 0) {
                            if ($productColor->pivot->quantity >= $this->qtyCount) {
                                Cart::create([
                                    'user_id' => Auth::user()->id,
                                    'product_id' => $productId,
                                    'product_color_id' => $this->productColorId,
                                    'quantity' => $this->qtyCount,
                                ]);
                                
                                $this->dispatch('cart-updated');
                                $this->dispatch('notify', [
                                    'text' => 'Produit bien ajouté au panier',
                                    'type' => 'success',
                                    'status' => '200'
                                ]);
                                
                                $this->closeProductModal();
                            } else {
                                $this->dispatch('notify', [
                                    'text' => 'La quantité disponible est de '.$productColor->pivot->quantity,
                                    'type' => 'warning',
                                    'status' => ''
                                ]);
                            }
                        } else {
                            $this->dispatch('notify', [
                                'text' => 'Ce produit avec cette couleur est en rupture de stock',
                                'type' => 'warning',
                                'status' => ''
                            ]);
                        }
                    } else {
                        $this->dispatch('notify', [
                            'text' => 'Veuillez sélectionner une couleur du produit.',
                            'type' => 'info',
                            'position' => ''
                        ]);
                    }
                } else {
                    // Product without colors
                    $existingCartItem = Cart::where('user_id', auth()->user()->id)
                        ->where('product_id', $productId)
                        ->whereNull('product_color_id')
                        ->first();
                        
                    if ($existingCartItem) {
                        $this->dispatch('notify', [
                            'text' => 'Ce produit figure déjà dans le panier.',
                            'type' => 'warning',
                            'position' => ''
                        ]);
                        return;
                    }
                    
                    if ($product->stock_quantity > 0) {
                        if ($product->stock_quantity >= $this->qtyCount) {
                            Cart::create([
                                'user_id' => Auth::user()->id,
                                'product_id' => $productId,
                                'quantity' => $this->qtyCount,
                            ]);
                            
                            $this->dispatch('cart-updated');
                            $this->dispatch('notify', [
                                'position' => '',
                                'text' => 'Produit bien ajouté au panier',
                                'type' => 'success'
                            ]);
                            
                            $this->closeProductModal();
                        } else {
                            $this->dispatch('notify', [
                                'text' => 'La quantité disponible est de '.$product->stock_quantity,
                                'type' => 'warning',
                                'status' => ''
                            ]);
                        }
                    } else {
                        $this->dispatch('notify', [
                            'text' => 'Ce produit est en rupture de stock',
                            'type' => 'warning',
                            'position' => ''
                        ]);
                    }
                }
            } else {
                $this->dispatch('notify', [
                    'text' => 'Ce produit n\'existe pas.',
                    'type' => 'error',
                    'status' => 404
                ]);
            }
        } else {
            $this->dispatch('notify', [
                'text' => 'Connectez-vous pour pouvoir ajouter au panier',
                'type' => 'error',
                'status' => 401
            ]);
        }
    }

    public function incrementQty($id)
    {
        $cartData = Cart::where('user_id', auth()->user()->id)->where('id', $id)->first();
        
        if ($cartData) {
            if ($cartData->product_color_id) {
                $productColor = $cartData->product->colors()->where('color_id', $cartData->product_color_id)->first();
                
                if ($productColor && $productColor->pivot->quantity > $cartData->quantity) {
                    $cartData->increment('quantity');
                    $this->dispatch('cart-updated');
                    $this->dispatch('notify', [
                        'text' => 'Produit mis à jour',
                        'type' => 'success',
                        'status' => 200
                    ]);
                } else {
                    $this->dispatch('notify', [
                        'text' => 'Il ne reste que '.number_format($productColor->pivot->quantity).' article(s)',
                        'type' => 'warning',
                        'status' => ''
                    ]);
                }
            } else {
                if ($cartData->product->stock_quantity > $cartData->quantity) {
                    $cartData->increment('quantity');
                    $this->dispatch('cart-updated');
                    $this->dispatch('notify', [
                        'text' => 'Produit mis à jour',
                        'type' => 'success',
                        'status' => 200
                    ]);
                } else {
                    $this->dispatch('notify', [
                        'text' => 'Il ne reste que '.number_format($cartData->product->stock_quantity).' article(s)',
                        'type' => 'warning',
                        'status' => ''
                    ]);
                }
            }
        } else {
            $this->dispatch('notify', [
                'text' => 'Echec de la mise à jour. Veuillez rééssayer.',
                'type' => 'error',
                'status' => '404'
            ]);
        }
    }

    public function decrementQty($id)
    {
        $cartData = Cart::where('user_id', auth()->user()->id)->where('id', $id)->first();
        
        if ($cartData) {
            if ($cartData->quantity > 1) {
                $cartData->decrement('quantity');
                $this->dispatch('cart-updated');
                $this->dispatch('notify', [
                    'text' => 'Produit mis à jour',
                    'type' => 'success',
                    'status' => 200
                ]);
            } else {
                $this->removeFromCart($id);
            }
        } else {
            $this->dispatch('notify', [
                'text' => 'Echec de la mise à jour. Veuillez rééssayer.',
                'type' => 'error',
                'status' => '404'
            ]);
        }
    }

    public function removeFromCart($cartId)
    {
        $cart = Cart::where('id', $cartId)->where('user_id', auth()->user()->id)->first();
        
        if ($cart) {
            $cart->delete();
            $this->dispatch('cart-updated');
            $this->dispatch('notify', [
                'text' => 'Ce produit est bien supprimé de votre panier',
                'type' => 'success',
                'status' => 200
            ]);
        } else {
            $this->dispatch('notify', [
                'text' => 'Echec de la tentative de suppression. Veuillez rééssayer.',
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function clearCart()
    {
        Cart::where('user_id', auth()->user()->id)->delete();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'text' => 'Panier vidé avec succès',
            'type' => 'success',
            'status' => 200
        ]);
    }

    public function checkout()
    {
        // Implement checkout logic here
        /* $this->dispatch('notify', [
            'text' => 'Fonctionnalité de paiement à implémenter',
            'type' => 'info',
            'status' => 200
        ]); */
        return $this->redirect('/admin/pos/checkout', navigate: true);
    }
    
    public function render()
    {
        $query = Product::query()
            ->with(['category', 'brand', 'images'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                return $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        $products = $query->where('status', 'active')->paginate($this->perPage);
        
        $categories = Category::orderBy('name')->get();
        $colors = Color::where('is_active', true)->get();
        
        $this->checkCartCount();
        
        return view('livewire.admin.pos.sales-manager', [
            'products' => $products,
            'categories' => $categories,
            'colors' => $colors,
        ]);
    }
}
