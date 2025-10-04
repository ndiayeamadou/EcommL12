<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductColor;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderDetails extends Component
{
    public Order $order;
    
    // Modals
    public bool $showEditModal = false;
    public bool $showStatusModal = false;
    public bool $showAddItemModal = false;
    public bool $showDeleteItemModal = false;
    public bool $showStockDetailsModal = false;
    
    // Données sélectionnées
    public ?OrderItem $selectedItem = null;
    
    // Édition de commande
    #[Validate('required|string|max:255')]
    public string $fullname = '';
    
    #[Validate('required|email|max:255')]
    public string $email = '';
    
    #[Validate('nullable|string|max:20')]
    public string $phone = '';
    
    #[Validate('nullable|string|max:500')]
    public string $address = '';
    
    #[Validate('nullable|string|max:100')]
    public string $city = '';
    
    #[Validate('nullable|string|max:10')]
    public string $postal_code = '';
    
    #[Validate('required|in:cash,card,mobile,bank_transfer')]
    public string $payment_mode = '';
    
    #[Validate('nullable|string|max:100')]
    public string $payment_id = '';
    
    // Gestion du statut
    #[Validate('required|string|max:100')]
    public string $status_message = '';
    
    public string $status_note = '';
    public string $previousStatus = '';
    public bool $showStockWarning = false;
    public string $stockWarningMessage = '';
    public array $stockImpactDetails = [];
    
    // Ajout d'article
    public int $product_id = 0;
    public int $product_color_id = 0;
    public int $quantity = 1;
    public float $price = 0;
    
    // Données calculées
    public float $orderTotal = 0;
    public int $totalItems = 0;
    public array $statusHistory = [];
    public array $availableProducts = [];
    public array $availableStatuses = [];
    
    // Configuration
    protected array $rules = [
        'fullname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:10',
        'payment_mode' => 'required|in:cash,card,mobile,bank_transfer',
        'payment_id' => 'nullable|string|max:100',
        'status_message' => 'required|string|max:100',
        'product_id' => 'required|integer|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ];

    public function mount(Order $order): void
    {
        // CORRECTION : Charger les relations correctement
        $this->order = $order->load([
            'user', 
            'orderItems.product.images', 
            'orderItems.productColor.color', // Cette relation est correcte
            'agent',
            'updatedBy'
        ]);
        
        $this->initializeComponent();
    }

    private function initializeComponent(): void
    {
        $this->loadOrderData();
        $this->calculateTotals();
        $this->loadStatusHistory();
        $this->loadAvailableData();
    }

    private function loadOrderData(): void
    {
        $this->fullname = $this->order->fullname;
        $this->email = $this->order->email ?? '';
        $this->phone = $this->order->phone ?? '';
        $this->address = $this->order->address ?? '';
        $this->city = $this->order->city ?? '';
        $this->postal_code = $this->order->postal_code ?? '';
        $this->payment_mode = $this->order->payment_mode ?? 'cash';
        $this->payment_id = $this->order->payment_id ?? '';
        $this->status_message = $this->order->status_message ?? 'En cours de traitement';
        $this->previousStatus = $this->order->status_message;
    }

    private function calculateTotals(): void
    {
        $this->orderTotal = $this->order->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        $this->totalItems = $this->order->orderItems->sum('quantity');
    }

    private function loadStatusHistory(): void
    {
        $this->statusHistory = [
            [
                'status' => 'En cours de traitement',
                'date' => $this->order->created_at,
                'note' => 'Commande créée',
                'user' => $this->getAgentName($this->order->agent)
            ]
        ];

        // Ajouter les mises à jour de statut
        if ($this->order->updated_at != $this->order->created_at && $this->order->updatedBy) {
            $this->statusHistory[] = [
                'status' => $this->order->status_message,
                'date' => $this->order->updated_at,
                'note' => $this->order->status_note ?? 'Mise à jour du statut',
                'user' => $this->getAgentName($this->order->updatedBy)
            ];
        }
    }

    private function getAgentName(?User $agent): string
    {
        if (!$agent) return 'Système';
        
        return trim($agent->firstname . ' ' . $agent->lastname) . 
               ($agent->customer_number ? ' (' . $agent->customer_number . ')' : '');
    }

    private function loadAvailableData(): void
    {
        $this->availableStatuses = [
            'En cours de traitement',
            'Terminé',
            'Annulé',
        ];

        // CORRECTION : Charger les produits avec les relations correctes
        $this->availableProducts = Product::active()
            ->with(['images', 'colors']) // Pas besoin de .color ici
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    // ==================== ACTIONS PRINCIPALES ====================

    public function showEditOrder(): void
    {
        $this->showEditModal = true;
    }

    public function updateOrder(): void
    {
        $this->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'payment_mode' => 'required|in:cash,card,mobile,bank_transfer',
            'payment_id' => 'nullable|string|max:100',
        ]);

        try {
            $this->order->update([
                'fullname' => $this->fullname,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'payment_mode' => $this->payment_mode,
                'payment_id' => $this->payment_id,
                'updated_by' => auth()->id(),
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'text' => 'Commande mise à jour avec succès.'
            ]);

            $this->showEditModal = false;

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'text' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
        }
    }

    public function showUpdateStatus(): void
    {
        $this->previousStatus = $this->order->status_message;
        $this->status_message = $this->order->status_message;
        $this->status_note = '';
        $this->showStockWarning = false;
        $this->stockWarningMessage = '';
        $this->stockImpactDetails = [];
        
        $this->showStatusModal = true;
    }

    public function updatedStatusMessage($newStatus): void
    {
        $this->analyzeStockImpact($newStatus);
    }

    private function analyzeStockImpact(string $newStatus): void
    {
        $this->showStockWarning = false;
        $this->stockWarningMessage = '';
        $this->stockImpactDetails = [];

        // Annulation d'une commande active
        if ($newStatus === 'Annulé' && $this->previousStatus !== 'Annulé') {
            $this->prepareStockReturn();
        }
        // Réactivation d'une commande annulée
        elseif ($newStatus !== 'Annulé' && $this->previousStatus === 'Annulé') {
            $this->prepareStockDeduction();
        }
    }

    private function prepareStockReturn(): void
    {
        $this->showStockWarning = true;
        $this->stockWarningMessage = "✅ L'annulation retournera automatiquement les articles au stock.";
        
        $this->stockImpactDetails = [
            'type' => 'return',
            'items' => [],
            'totalItems' => 0
        ];

        foreach ($this->order->orderItems as $item) {
            if ($item->product) {
                // CORRECTION : Accéder correctement au nom de la couleur
                $colorName = null;
                if ($item->productColor && $item->productColor->color) {
                    $colorName = $item->productColor->color->name;
                }
                
                $this->stockImpactDetails['items'][] = [
                    'product' => $item->product->name,
                    'quantity' => $item->quantity,
                    'color' => $colorName,
                    'manages_stock' => $item->product->manage_stock
                ];
                $this->stockImpactDetails['totalItems'] += $item->quantity;
            }
        }
    }

    private function prepareStockDeduction(): void
    {
        $stockCheck = $this->checkStockAvailability();
        
        if ($stockCheck['success']) {
            $this->showStockWarning = true;
            $this->stockWarningMessage = "📦 La réactivation déduira à nouveau les articles du stock.";
        } else {
            $this->showStockWarning = true;
            $this->stockWarningMessage = "❌ Stock insuffisant : " . $stockCheck['message'];
        }

        $this->stockImpactDetails = [
            'type' => 'deduction',
            'items' => [],
            'totalItems' => 0,
            'stock_available' => $stockCheck['success']
        ];

        foreach ($this->order->orderItems as $item) {
            if ($item->product) {
                // CORRECTION : Accéder correctement aux données de couleur
                $colorName = null;
                $colorStock = null;
                
                if ($item->productColor && $item->productColor->color) {
                    $colorName = $item->productColor->color->name;
                    $colorStock = $item->productColor->quantity;
                }
                
                $this->stockImpactDetails['items'][] = [
                    'product' => $item->product->name,
                    'quantity' => $item->quantity,
                    'current_stock' => $item->product->stock_quantity,
                    'color' => $colorName,
                    'color_stock' => $colorStock,
                    'manages_stock' => $item->product->manage_stock,
                    'sufficient_stock' => $item->product->stock_quantity >= $item->quantity
                ];
                $this->stockImpactDetails['totalItems'] += $item->quantity;
            }
        }
    }

    private function checkStockAvailability(): array
    {
        foreach ($this->order->orderItems as $orderItem) {
            if ($orderItem->product && $orderItem->product->manage_stock) {
                // Vérifier le stock général
                if ($orderItem->product->stock_quantity < $orderItem->quantity) {
                    return [
                        'success' => false,
                        'message' => "{$orderItem->product->name} : Stock disponible {$orderItem->product->stock_quantity}, requis {$orderItem->quantity}"
                    ];
                }

                // Vérifier le stock de couleur
                if ($orderItem->product_color_id && $orderItem->productColor) {
                    $colorStock = $orderItem->productColor->quantity ?? 0;
                    if ($colorStock < $orderItem->quantity) {
                        $colorName = $orderItem->productColor->color->name ?? 'couleur sélectionnée';
                        return [
                            'success' => false,
                            'message' => "{$orderItem->product->name} ($colorName) : Stock couleur disponible $colorStock, requis {$orderItem->quantity}"
                        ];
                    }
                }
            }
        }

        return ['success' => true];
    }

    public function updateStatus(): void
    {
        $this->validate([
            'status_message' => 'required|string|max:100',
        ]);

        // Vérifier si le statut a changé
        if ($this->status_message === $this->previousStatus) {
            $this->dispatch('notify', [
                'type' => 'info',
                'text' => 'Le statut est déjà "' . $this->status_message . '"'
            ]);
            $this->closeStatusModal();
            return;
        }

        try {
            // Gérer l'impact sur le stock
            $stockResult = $this->executeStockManagement();
            if (!$stockResult['success']) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'text' => $stockResult['message']
                ]);
                return;
            }

            // Mettre à jour le statut
            $this->order->update([
                'status_message' => $this->status_message,
                'status_note' => $this->status_note ?: null,
                'updated_by' => auth()->id(),
            ]);

            // Recharger les données
            $this->order->refresh();
            $this->loadStatusHistory();

            $this->dispatch('notify', [
                'type' => 'success',
                'text' => 'Statut mis à jour. ' . $stockResult['message']
            ]);

            $this->closeStatusModal();

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour statut commande #{$this->order->id}: " . $e->getMessage());
            
            $this->dispatch('notify', [
                'type' => 'error',
                'text' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
        }
    }

    private function executeStockManagement(): array
    {
        // Annulation : retour au stock
        if ($this->status_message === 'Annulé' && $this->previousStatus !== 'Annulé') {
            return $this->returnItemsToStock();
        }
        
        // Réactivation : déduction du stock
        if ($this->status_message !== 'Annulé' && $this->previousStatus === 'Annulé') {
            return $this->deductItemsFromStock();
        }
        
        return ['success' => true, 'message' => ''];
    }

    private function returnItemsToStock(): array
    {
        try {
            $returnedItems = [];
            
            foreach ($this->order->orderItems as $orderItem) {
                if ($orderItem->product && $orderItem->product->manage_stock) {
                    // Retour au stock principal
                    $oldStock = $orderItem->product->stock_quantity;
                    $orderItem->product->updateStock($orderItem->quantity, 'increase');
                    
                    $returnedItems[] = [
                        'product' => $orderItem->product->name,
                        'quantity' => $orderItem->quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $orderItem->product->fresh()->stock_quantity
                    ];

                    // Retour au stock de couleur
                    if ($orderItem->product_color_id && $orderItem->productColor) {
                        $orderItem->product->updateColorStock(
                            $orderItem->product_color_id, 
                            $orderItem->quantity, 
                            'increase'
                        );
                    }
                }
            }

            Log::info("Commande #{$this->order->id} annulée - Stock retourné", [
                'items' => $returnedItems,
                'user_id' => auth()->id()
            ]);

            return [
                'success' => true, 
                'message' => count($returnedItems) . ' article(s) retourné(s) au stock.'
            ];

        } catch (\Exception $e) {
            Log::error("Erreur retour stock commande #{$this->order->id}", ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur lors du retour du stock: ' . $e->getMessage()
            ];
        }
    }

    private function deductItemsFromStock(): array
    {
        try {
            // Vérifier la disponibilité
            $stockCheck = $this->checkStockAvailability();
            if (!$stockCheck['success']) {
                return $stockCheck;
            }

            $deductedItems = [];
            
            foreach ($this->order->orderItems as $orderItem) {
                if ($orderItem->product && $orderItem->product->manage_stock) {
                    // Déduction du stock principal
                    $oldStock = $orderItem->product->stock_quantity;
                    $orderItem->product->updateStock($orderItem->quantity, 'decrease');
                    
                    $deductedItems[] = [
                        'product' => $orderItem->product->name,
                        'quantity' => $orderItem->quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $orderItem->product->fresh()->stock_quantity
                    ];

                    // Déduction du stock de couleur
                    if ($orderItem->product_color_id && $orderItem->productColor) {
                        $orderItem->product->updateColorStock(
                            $orderItem->product_color_id, 
                            $orderItem->quantity, 
                            'decrease'
                        );
                    }
                }
            }

            Log::info("Commande #{$this->order->id} réactivée - Stock déduit", [
                'items' => $deductedItems,
                'user_id' => auth()->id()
            ]);

            return [
                'success' => true, 
                'message' => count($deductedItems) . ' article(s) déduit(s) du stock.'
            ];

        } catch (\Exception $e) {
            Log::error("Erreur déduction stock commande #{$this->order->id}", ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur lors de la déduction du stock: ' . $e->getMessage()
            ];
        }
    }

    public function showStockImpactDetails(): void
    {
        $this->showStockDetailsModal = true;
    }

    // ==================== GESTION DES ARTICLES ====================

    public function showAddItem(): void
    {
        $this->resetItemForm();
        $this->showAddItemModal = true;
    }

    public function addOrderItem(): void
    {
        $this->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            OrderItem::create([
                'order_id' => $this->order->id,
                'product_id' => $this->product_id,
                'product_color_id' => $this->product_color_id ?: null,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ]);

            $this->order->refresh();
            $this->calculateTotals();

            $this->dispatch('notify', [
                'type' => 'success',
                'text' => 'Article ajouté avec succès.'
            ]);

            $this->closeAddItemModal();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'text' => 'Erreur lors de l\'ajout: ' . $e->getMessage()
            ]);
        }
    }

    public function confirmDeleteItem(OrderItem $item): void
    {
        $this->selectedItem = $item;
        $this->showDeleteItemModal = true;
    }

    public function deleteOrderItem(): void
    {
        if (!$this->selectedItem) return;

        try {
            $itemName = $this->selectedItem->product->name ?? 'Article';
            $this->selectedItem->delete();
            
            $this->order->refresh();
            $this->calculateTotals();

            $this->dispatch('notify', [
                'type' => 'success',
                'text' => "'$itemName' supprimé avec succès."
            ]);

            $this->closeDeleteItemModal();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'text' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
    }

    public function updatedProductId($productId): void
    {
        if ($productId > 0) {
            $product = Product::find($productId);
            if ($product) {
                $this->price = $product->sale_price ?: $product->price;
            }
        }
    }

    // ==================== ACTIONS PDF & EMAIL ====================

    public function generatePDF()
    {
        try {
            $data = [
                'order' => $this->order,
                'orderTotal' => $this->orderTotal,
                'totalItems' => $this->totalItems,
                'generatedAt' => now()->format('d/m/Y à H:i'),
            ];

            $pdf = Pdf::loadView('pdf.order-invoice-demie-page', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                ]);

            $filename = 'commande-' . $this->order->id . '-' . now()->format('Y-m-d') . '.pdf';
            
            return response()->streamDownload(
                fn () => print($pdf->output()),
                $filename
            );

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'text' => 'Erreur génération PDF: ' . $e->getMessage()
            ]);
        }
    }

    // ==================== MÉTHODES UTILITAIRES ====================

    private function resetItemForm(): void
    {
        $this->product_id = 0;
        $this->product_color_id = 0;
        $this->quantity = 1;
        $this->price = 0;
        $this->resetErrorBag();
    }

    // ==================== FERMETURE DES MODALS ====================

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetErrorBag();
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->status_note = '';
        $this->showStockWarning = false;
        $this->stockWarningMessage = '';
        $this->stockImpactDetails = [];
        $this->resetErrorBag();
    }

    public function closeAddItemModal(): void
    {
        $this->showAddItemModal = false;
        $this->resetItemForm();
    }

    public function closeDeleteItemModal(): void
    {
        $this->showDeleteItemModal = false;
        $this->selectedItem = null;
    }

    public function closeStockDetailsModal(): void
    {
        $this->showStockDetailsModal = false;
    }

    // ==================== RENDU ====================

    public function render()
    {
        return view('livewire.admin.orders.order-details');
    }
}
