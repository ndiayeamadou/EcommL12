<?php

namespace App\Livewire\Admin\Pos;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class PosInterface extends Component
{
    use WithPagination;

    // Propriétés du panier
    public $cart = [];
    public $cartTotal = 0;
    public $cartSubtotal = 0;
    public $taxAmount = 0;
    public $discountAmount = 0;
    
    // Propriétés du client
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    
    // Propriétés de paiement
    public $paymentMethod = 'cash';
    public $paidAmount = 0;
    public $changeAmount = 0;
    public $notes = '';
    
    // Propriétés de recherche et filtrage
    public $search = '';
    public $selectedCategory = '';
    public $showOutOfStock = false;
    
    // Propriétés d'interface
    public $showPaymentModal = false;
    public $showCustomerModal = false;
    public $processingPayment = false;
    
    // Propriétés de configuration
    public $taxRate = 18; // 18% TVA
    public $perPage = 12;

    protected $rules = [
        'customerName' => 'nullable|string|max:255',
        'customerEmail' => 'nullable|email|max:255',
        'customerPhone' => 'nullable|string|max:20',
        'paidAmount' => 'required|numeric|min:0',
        'paymentMethod' => 'required|in:cash,card,mobile_money,bank_transfer',
        'notes' => 'nullable|string|max:500'
    ];

    protected $listeners = [
        'cartUpdated' => 'updateCartTotals',
        'productScanned' => 'addProductByBarcode'
    ];

    public function mount()
    {
        $this->updateCartTotals();
    }

    public function render()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $productsQuery = Product::with('category')
            ->where('status', 'active')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when(!$this->showOutOfStock, function($query) {
                $query->where('stock_quantity', '>', 0);
            });

        $products = $productsQuery->paginate($this->perPage);

        return view('livewire.admin.pos.pos-interface', [
            'products' => $products,
            'categories' => $categories,
            'cartCount' => count($this->cart),
            'paymentMethods' => [
                'cash' => 'Espèces',
                'card' => 'Carte Bancaire',
                'mobile_money' => 'Mobile Money',
                'bank_transfer' => 'Virement Bancaire'
            ]
        ]);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock_quantity <= 0) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Produit indisponible ou en rupture de stock'
            ]);
            return;
        }

        $cartKey = $productId;
        
        if (isset($this->cart[$cartKey])) {
            if ($this->cart[$cartKey]['quantity'] < $product->stock_quantity) {
                $this->cart[$cartKey]['quantity']++;
            } else {
                $this->dispatch('show-notification', [
                    'type' => 'warning',
                    'message' => 'Stock insuffisant pour ce produit'
                ]);
                return;
            }
        } else {
            $this->cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->sale_price ?? $product->price,
                'original_price' => $product->price,
                'quantity' => 1,
                'image' => $product->images ? json_decode($product->images)[0] ?? null : null,
                'stock_quantity' => $product->stock_quantity
            ];
        }

        $this->updateCartTotals();
        $this->dispatch('cart-updated');
        
        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => 'Produit ajouté au panier'
        ]);
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartKey);
            return;
        }

        if (isset($this->cart[$cartKey])) {
            $maxQuantity = $this->cart[$cartKey]['stock_quantity'];
            
            if ($quantity > $maxQuantity) {
                $this->dispatch('show-notification', [
                    'type' => 'warning',
                    'message' => "Quantité maximum disponible: {$maxQuantity}"
                ]);
                $quantity = $maxQuantity;
            }
            
            $this->cart[$cartKey]['quantity'] = $quantity;
            $this->updateCartTotals();
        }
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        $this->updateCartTotals();
        
        $this->dispatch('show-notification', [
            'type' => 'info',
            'message' => 'Produit retiré du panier'
        ]);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->updateCartTotals();
        
        $this->dispatch('show-notification', [
            'type' => 'info',
            'message' => 'Panier vidé'
        ]);
    }

    public function updateCartTotals()
    {
        $this->cartSubtotal = 0;
        
        foreach ($this->cart as $item) {
            $this->cartSubtotal += $item['price'] * $item['quantity'];
        }
        
        $this->taxAmount = ($this->cartSubtotal - $this->discountAmount) * ($this->taxRate / 100);
        $this->cartTotal = $this->cartSubtotal + $this->taxAmount - $this->discountAmount;
        
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $this->changeAmount = max(0, $this->paidAmount - $this->cartTotal);
    }

    public function updatedPaidAmount()
    {
        $this->calculateChange();
    }

    public function showPayment()
    {
        if (empty($this->cart)) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Le panier est vide'
            ]);
            return;
        }

        $this->paidAmount = $this->cartTotal;
        $this->calculateChange();
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->validate();

        if ($this->paidAmount < $this->cartTotal) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Montant insuffisant'
            ]);
            return;
        }

        $this->processingPayment = true;

        try {
            // Créer la vente
            $sale = Sale::create([
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'subtotal' => $this->cartSubtotal,
                'tax_amount' => $this->taxAmount,
                'discount_amount' => $this->discountAmount,
                'total_amount' => $this->cartTotal,
                'paid_amount' => $this->paidAmount,
                'change_amount' => $this->changeAmount,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
                'notes' => $this->notes,
                'cashier_name' => auth()->user()->name ?? 'Caissier',
                'user_id' => auth()->id()
            ]);

            // Créer les items de vente et gérer le stock
            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);
                
                // Créer l'item de vente
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount_per_item' => 0,
                    'product_snapshot' => $product->toArray()
                ]);

                // Mettre à jour le stock
                $quantityBefore = $product->stock_quantity;
                $product->decrement('stock_quantity', $item['quantity']);
                $product->increment('sales_count', $item['quantity']);
                
                // Créer la transaction de stock
                StockTransaction::create([
                    'product_id' => $product->id,
                    'sale_id' => $sale->id,
                    'type' => 'sale',
                    'quantity_before' => $quantityBefore,
                    'quantity_change' => -$item['quantity'],
                    'quantity_after' => $quantityBefore - $item['quantity'],
                    'reason' => 'Vente POS',
                    'reference' => $sale->sale_number
                ]);
            }

            // Réinitialiser l'interface
            $this->resetAfterSale();
            
            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Vente enregistrée avec succès!'
            ]);

            $this->dispatch('print-receipt', ['saleId' => $sale->id]);

        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ]);
        } finally {
            $this->processingPayment = false;
        }
    }

    private function resetAfterSale()
    {
        $this->cart = [];
        $this->customerName = '';
        $this->customerEmail = '';
        $this->customerPhone = '';
        $this->paidAmount = 0;
        $this->changeAmount = 0;
        $this->notes = '';
        $this->discountAmount = 0;
        $this->showPaymentModal = false;
        $this->updateCartTotals();
    }

    public function applyDiscount($amount)
    {
        $this->discountAmount = max(0, min($amount, $this->cartSubtotal));
        $this->updateCartTotals();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId == $this->selectedCategory ? '' : $categoryId;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function addProductByBarcode($barcode)
    {
        $product = Product::where('sku', $barcode)->first();
        
        if ($product) {
            $this->addToCart($product->id);
        } else {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Produit non trouvé: ' . $barcode
            ]);
        }
    }
}
