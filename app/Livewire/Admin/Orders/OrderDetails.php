<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OrderDetails extends Component
{
    public Order $order;
    public bool $showEditModal = false;
    public bool $showStatusModal = false;
    public bool $showAddItemModal = false;
    public bool $showDeleteItemModal = false;
    public ?OrderItem $selectedItem = null;
    
    // Édition de la commande
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
    
    // Ajout d'article
    public int $product_id = 0;
    public int $product_color_id = 0;
    public int $quantity = 1;
    public float $price = 0;
    
    // Statistiques et données
    public array $statusHistory = [];
    public array $availableProducts = [];
    public array $availableStatuses = [];
    public float $orderTotal = 0;
    public int $totalItems = 0;

    protected array $rules = [
        'fullname' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
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

    protected array $messages = [
        'fullname.required' => 'Le nom complet est obligatoire.',
        'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'Veuillez saisir une adresse email valide.',
        'payment_mode.required' => 'Le mode de paiement est obligatoire.',
        'payment_mode.in' => 'Mode de paiement invalide.',
        'status_message.required' => 'Le statut est obligatoire.',
        'product_id.required' => 'Veuillez sélectionner un produit.',
        'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
        'quantity.required' => 'La quantité est obligatoire.',
        'quantity.min' => 'La quantité doit être au moins 1.',
        'price.required' => 'Le prix est obligatoire.',
        'price.min' => 'Le prix doit être positif.',
    ];

    public function mount(Order $order): void
    {
        $this->order = $order->load(['user', 'orderItems.product.images', 'orderItems.productColor.color']);
        $this->initializeData();
        $this->calculateTotals();
        $this->loadStatusHistory();
    }

    private function initializeData(): void
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

        $this->availableStatuses = [
            'En cours de traitement',
            'Confirmé',
            'En préparation',
            'Expédié',
            'En livraison',
            'Livré',
            'Terminé',
            'En attente de paiement',
            'Paiement confirmé',
            'Annulé',
            'Remboursé',
            'Retourné'
        ];

        $this->availableProducts = Product::active()
            ->with(['images', 'colors'])
            ->orderBy('name')
            ->get()
            ->toArray();
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
        // Simuler un historique de statut (à adapter selon votre implémentation)
        $this->statusHistory = [
            [
                'status' => 'En cours de traitement',
                'date' => $this->order->created_at,
                'note' => 'Commande créée',
                //'user' => 'Système'
                'user' => $this->order->agent->firstname . ' ' . $this->order->agent->lastname . ' ' . ($this->order->agent->customer_number)
            ]/* ,
            [
                'status' => $this->order->status_message,
                'date' => $this->order->created_at,
                'note' => $this->order->status_note,
                'user' => $this->order->agent_de_suivi->lastname ?? null . ' ' . $this->order->agent_de_suivi->firstname ?? null . ' ' . ($this->order->agent_de_suivi->customer_number ?? null)
            ] */
        ];
    }

    public function showEditOrder(): void
    {
        $this->showEditModal = true;
    }

    public function updateOrder(): void
    {
        $this->validate([
            'fullname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
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
            ]);

            $this->dispatch('notify', [
                'text' => 'Commande mise à jour avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->showEditModal = false;

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function showUpdateStatus(): void
    {
        $this->showStatusModal = true;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'status_message' => 'required|string|max:100',
        ]);

        try {
            $this->order->update([
                'status_message' => $this->status_message,
                'status_note' => $this->status_note,
                'updated_by' => auth()->user()->id,
            ]);

            // Ajouter à l'historique
            $this->statusHistory[] = [
                'status' => $this->status_message,
                'date' => now(),
                'note' => $this->status_note,
                //'user' => auth()->user()->name ?? 'Admin'
                'user' => auth()->user()->firstname .' '. auth()->user()->lastname ?? 'Admin'
            ];

            $this->dispatch('notify', [
                'text' => 'Statut mis à jour avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->showStatusModal = false;
            $this->status_note = '';

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

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
                'text' => 'Article ajouté avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->showAddItemModal = false;
            $this->resetItemForm();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de l\'ajout: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
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
        if (!$this->selectedItem) {
            return;
        }

        try {
            $this->selectedItem->delete();
            $this->order->refresh();
            $this->calculateTotals();

            $this->dispatch('notify', [
                'text' => 'Article supprimé avec succès.',
                'type' => 'success',
                'status' => 200
            ]);

            $this->showDeleteItemModal = false;
            $this->selectedItem = null;

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la suppression: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function generatePDF()
    {
        try {
            $data = [
                'order' => $this->order,
                'orderTotal' => $this->orderTotal,
                'totalItems' => $this->totalItems,
                'generatedAt' => now()->format('d/m/Y à H:i'),
            ];

            //$pdf = Pdf::loadView('pdf.order-invoice', $data)
            $pdf = Pdf::loadView('pdf.order-invoice-demie-page', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);

            $filename = 'commande-' . $this->order->id . '-' . now()->format('Y-m-d') . '.pdf';
            
            return response()->streamDownload(
                fn () => print($pdf->output()),
                $filename,
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de la génération PDF: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function sendOrderEmail(): void
    {
        if (!$this->order->email) {
            $this->dispatch('notify', [
                'text' => 'Aucune adresse email disponible pour ce client.',
                'type' => 'warning',
                'status' => 400
            ]);
            return;
        }

        try {
            // Générer le PDF en mémoire
            $data = [
                'order' => $this->order,
                'orderTotal' => $this->orderTotal,
                'totalItems' => $this->totalItems,
                'generatedAt' => now()->format('d/m/Y à H:i'),
            ];

            $pdf = Pdf::loadView('pdf.order-invoice', $data)
                ->setPaper('a4', 'portrait');

            // Envoyer l'email avec le PDF en pièce jointe
            Mail::send('emails.order-confirmation', $data, function ($message) use ($pdf) {
                $message->to($this->order->email, $this->order->fullname)
                    ->subject('Confirmation de commande #' . $this->order->id)
                    ->attachData($pdf->output(), 'commande-' . $this->order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            $this->dispatch('notify', [
                'text' => 'Email envoyé avec succès à ' . $this->order->email,
                'type' => 'success',
                'status' => 200
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
        }
    }

    public function updatedProductId(): void
    {
        if ($this->product_id > 0) {
            $product = Product::find($this->product_id);
            if ($product) {
                $this->price = $product->sale_price ?: $product->price;
            }
        }
    }

    private function resetItemForm(): void
    {
        $this->product_id = 0;
        $this->product_color_id = 0;
        $this->quantity = 1;
        $this->price = 0;
        $this->resetErrorBag(['product_id', 'quantity', 'price']);
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetErrorBag();
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->status_note = '';
        $this->resetErrorBag(['status_message']);
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

    public function render()
    {
        return view('livewire.admin.orders.order-details');
    }
}