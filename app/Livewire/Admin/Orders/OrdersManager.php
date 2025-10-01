<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Carbon\Carbon;

class OrdersManager extends Component
{
    use WithPagination;
    
    // Filtres et tri
    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $paymentFilter = '';
    public $agentFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;
    
    // Modal et détails
    public $selectedOrder = null;
    public $showOrderModal = false;
    public $showDeleteModal = false;
    public $orderToDelete = null;
    
    // Mise à jour de statut
    public $newStatus = '';
    public $statusUpdateReason = '';
    
    // Statistiques
    public $totalOrders = 0;
    public $totalOrdersToday = 0;
    public $totalRevenue = 0;
    public $totalRevenueToday = 0;
    public $pendingOrders = 0, $pendingOrdersToday = 0;
    public $completedOrders = 0, $completedOrdersToday = 0;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'paymentFilter' => ['except' => ''],
        'agentFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
    ];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $today = now()->format('Y-m-d');

        $this->totalOrders = Order::count();

        $this->totalRevenue = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum(\DB::raw('order_items.quantity * order_items.price'));

        $this->pendingOrders = Order::whereIn('status_message', ['En cours de traitement', 'En attente'])->count();

        $this->completedOrders = Order::where('status_message', 'Terminé')->count();
        //dd($this->totalRevenueToday);

        // Statistiques d'aujourd'hui
        $this->totalRevenueToday = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', today())
            ->sum(\DB::raw('order_items.quantity * order_items.price'));

        /* $this->totalRevenueToday = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', $today)
            ->sum(\DB::raw('order_items.quantity * order_items.price')); */
    
        $this->totalOrdersToday = Order::whereDate('created_at', $today)->count();
        
        $this->pendingOrdersToday = Order::whereDate('created_at', $today)
            ->whereIn('status_message', ['En cours de traitement', 'En attente'])
            ->count();
        
        $this->completedOrdersToday = Order::whereDate('created_at', $today)
            ->where('status_message', 'Terminé')
            ->count();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatingDateFilter()
    {
        $this->resetPage();
    }
    
    public function updatingPaymentFilter()
    {
        $this->resetPage();
    }
    
    public function updatingAgentFilter()
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

    public function showOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with([
            'user',
            'orderItems.product.images',
            'orderItems.productColor'
        ])->find($orderId);
        
        if ($this->selectedOrder) {
            $this->newStatus = $this->selectedOrder->status_message;
            $this->showOrderModal = true;
        }
    }

    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
        $this->newStatus = '';
        $this->statusUpdateReason = '';
    }

    public function updateOrderStatus()
    {
        if (!$this->selectedOrder || empty($this->newStatus)) {
            return;
        }

        $this->selectedOrder->update([
            'status_message' => $this->newStatus
        ]);

        $this->dispatch('notify', [
            'text' => 'Statut de la commande mis à jour avec succès',
            'type' => 'success',
            'status' => 200
        ]);

        $this->loadStatistics();
        $this->closeOrderModal();
    }

    public function confirmDelete($orderId)
    {
        $this->orderToDelete = Order::find($orderId);
        $this->showDeleteModal = true;
    }

    public function deleteOrder()
    {
        if ($this->orderToDelete) {
            // Supprimer les items de commande
            OrderItem::where('order_id', $this->orderToDelete->id)->delete();
            
            // Supprimer la commande
            $this->orderToDelete->delete();
            
            $this->dispatch('notify', [
                'text' => 'Commande supprimée avec succès',
                'type' => 'success',
                'status' => 200
            ]);
            
            $this->loadStatistics();
        }
        
        $this->showDeleteModal = false;
        $this->orderToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->orderToDelete = null;
    }

    public function exportOrders()
    {
        $this->dispatch('notify', [
            'text' => 'Export des commandes en cours...',
            'type' => 'info',
            'status' => 200
        ]);
    }

    public function getOrderTotalAttribute()
    {
        if (!$this->selectedOrder) return 0;
        
        return $this->selectedOrder->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    public function render()
    {
        $query = Order::query()
            ->with(['user', 'orderItems'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('tracking_no', 'like', '%' . $this->search . '%')
                      ->orWhere('fullname', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status_message', $this->statusFilter);
            })
            ->when($this->paymentFilter, function ($query) {
                return $query->where('payment_mode', $this->paymentFilter);
            })
            ->when($this->agentFilter, function ($query) {
                return $query->where('agent_id', $this->agentFilter);
            })
            ->when($this->dateFilter, function ($query) {
                switch ($this->dateFilter) {
                    case 'today':
                        return $query->whereDate('created_at', Carbon::today());
                    case 'week':
                        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    case 'month':
                        return $query->whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year);
                    case 'year':
                        return $query->whereYear('created_at', Carbon::now()->year);
                    default:
                        return $query;
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        $orders = $query->paginate($this->perPage);
        
        // Agents pour le filtre
        $agents = User::whereIn('type', [User::TYPE_ADMIN])
            ->orderBy('firstname')
            ->get();
        
        // Statuts disponibles
        $statuses = [
            'En cours de traitement',
            'Confirmé',
            'En préparation',
            'Expédié',
            'Livré',
            'Terminé',
            'Annulé',
            'Remboursé'
        ];
        
        return view('livewire.admin.orders.orders-manager', [
            'orders' => $orders,
            'agents' => $agents,
            'statuses' => $statuses,
        ]);
    }


    /* test gen pdf here - 22/09/25 */
    /* public function generatePDF(): void
    {
        try {
            $data = [
                'order' => $this->order,
                'orderTotal' => $this->orderTotal,
                'totalItems' => $this->totalItems,
                'generatedAt' => now()->format('d/m/Y à H:i'),
            ];

            $pdf = Pdf::loadView('pdf.order-invoice', $data)
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
    } */
    
}
