<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StandardDashboard extends Component
{
    public $selectedPeriod = 'month';
    public $currentUser;
    public $loading = true;

    public function mount(): void
    {
        $this->currentUser = auth()->user();
        
        // Vérification des permissions
        if (!$this->currentUser->hasAnyRole(['Vendeur', 'Caissier', 'Administrateur'])) {
            abort(403, 'Accès refusé. Rôle utilisateur insuffisant.');
        }
    }

    public function changePeriod($period): void
    {
        $this->selectedPeriod = $period;
        $this->loading = true;
    }

    public function render()
    {
        try {
            $dateFilter = $this->getDateFilter();
            
            $data = [
                'selectedPeriod' => $this->selectedPeriod,
                'currentUser' => $this->currentUser,
                'personalStats' => $this->getPersonalStats($dateFilter),
                'teamStats' => $this->getTeamStats($dateFilter),
                'performanceMetrics' => $this->getPerformanceMetrics($dateFilter),
                'topCustomers' => $this->getTopCustomers($dateFilter),
                'bestSellingProducts' => $this->getBestSellingProducts($dateFilter),
                'outOfStockProducts' => $this->getOutOfStockProducts(),
                'lowStockProducts' => $this->getLowStockProducts(),
                'recentOrders' => $this->getRecentOrders($dateFilter),
                'salesTrend' => $this->getSalesTrend($dateFilter),
                'loading' => $this->loading,
            ];

            $this->loading = false;

            return view('livewire.admin.dashboard.standard-dashboard', $data)
                ->layout('components.layouts.app');
                
        } catch (\Exception $e) {
            $this->loading = false;
            \Log::error('StandardDashboard error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getDateFilter(): Carbon
    {
        return match($this->selectedPeriod) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    /**
     * Méthode pour filtrer les commandes non annulées
     */
    private function excludeCancelledOrders($query)
    {
        return $query->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé']);
    }

    private function getPersonalStats($dateFilter): array
    {
        $personalStats = Order::where('orders.created_at', '>=', $dateFilter)
            ->where('agent_id', $this->currentUser->id)
            // Exclure les commandes annulées
            ->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('
                COUNT(DISTINCT orders.id) as order_count,
                COUNT(DISTINCT orders.user_id) as customer_count,
                COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue
            ')
            ->first();

        $revenue = $personalStats->revenue ?? 0;
        $orderCount = $personalStats->order_count ?? 0;
        $customerCount = $personalStats->customer_count ?? 0;
        $aov = $orderCount > 0 ? $revenue / $orderCount : 0;

        return [
            'revenue' => $revenue,
            'orders' => $orderCount,
            'customers' => $customerCount,
            'aov' => $aov,
            'today_orders' => Order::whereDate('created_at', Carbon::today())
                ->where('agent_id', $this->currentUser->id)
                // Exclure les commandes annulées pour aujourd'hui aussi
                ->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
                ->count(),
        ];
    }

    private function getTeamStats($dateFilter): array
    {
        // Calculer le CA de l'équipe (exclure les annulées)
        $teamRevenue = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            // Exclure les commandes annulées
            ->whereNotIn('orders.status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->sum(DB::raw('order_items.price * order_items.quantity')) ?? 0;

        // Nombre de commandes de l'équipe (exclure les annulées)
        $teamOrders = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->count();

        // Nombre de clients uniques (exclure les commandes annulées)
        $teamCustomers = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->distinct('user_id')
            ->count('user_id');

        return [
            'revenue' => $teamRevenue,
            'orders' => $teamOrders,
            'customers' => $teamCustomers,
            'products' => Product::count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 10)
                ->where('manage_stock', true)
                ->count(),
        ];
    }

    private function getPerformanceMetrics($dateFilter): array
    {
        $personalRevenue = $this->getPersonalStats($dateFilter)['revenue'];
        $teamRevenue = $this->getTeamStats($dateFilter)['revenue'];
        
        $contributionRate = $teamRevenue > 0 ? round(($personalRevenue / $teamRevenue) * 100, 1) : 0;
        
        // Calcul du classement dans l'équipe (exclure les annulées)
        $teamRanking = $this->calculateTeamRanking($dateFilter);
        $userRank = $teamRanking['user_rank'] ?? null;
        $totalAgents = $teamRanking['total_agents'] ?? 0;

        return [
            'contribution_rate' => $contributionRate,
            'user_rank' => $userRank,
            'total_agents' => $totalAgents,
            'is_top_performer' => $userRank === 1 && $totalAgents > 1,
        ];
    }

    private function calculateTeamRanking($dateFilter): array
    {
        $agentsPerformance = User::role(['Vendeur', 'Caissier'])
            ->select('users.id', 'users.firstname', 'users.lastname')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue')
            ->leftJoin('orders', function($join) use ($dateFilter) {
                $join->on('users.id', '=', 'orders.agent_id')
                     ->where('orders.created_at', '>=', $dateFilter)
                     // Exclure les commandes annulées
                     ->whereNotIn('orders.status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé']);
            })
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('users.id', 'users.firstname', 'users.lastname')
            ->orderByDesc('revenue')
            ->get();

        $userRank = null;
        foreach ($agentsPerformance as $index => $agent) {
            if ($agent->id === $this->currentUser->id) {
                $userRank = $index + 1;
                break;
            }
        }

        return [
            'user_rank' => $userRank,
            'total_agents' => $agentsPerformance->count(),
        ];
    }

    private function getTopCustomers($dateFilter)
    {
        return User::select('users.firstname', 'users.lastname', 'users.email')
            ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_spent')
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            // Exclure les commandes annulées
            ->whereNotIn('orders.status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function getBestSellingProducts($dateFilter): array
    {
        return Product::select('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as units_sold')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($dateFilter) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.created_at', '>=', $dateFilter)
                     // Exclure les commandes annulées
                     ->whereNotIn('orders.status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé']);
            })
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->orderByDesc('units_sold')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function getOutOfStockProducts(): array
    {
        return Product::where('stock_quantity', '<=', 0)
            ->select('id', 'name', 'sku', 'price', 'stock_quantity')
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getLowStockProducts(): array
    {
        return Product::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->where('manage_stock', true)
            ->select('id', 'name', 'sku', 'price', 'stock_quantity')
            ->orderBy('stock_quantity')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getRecentOrders($dateFilter): array
    {
        return Order::with(['user', 'orderItems.product'])
            ->where('created_at', '>=', $dateFilter)
            // Exclure les commandes annulées
            ->whereNotIn('status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->user->firstname . ' ' . $order->user->lastname,
                    'total' => $order->orderItems->sum(function($item) {
                        return $item->price * $item->quantity;
                    }),
                    'status' => $order->status_message,
                    'date' => $order->created_at->format('d/m/Y H:i'),
                    'items_count' => $order->orderItems->count(),
                    'is_my_order' => $order->agent_id === $this->currentUser->id,
                ];
            })
            ->toArray();
    }

    private function getSalesTrend($dateFilter): array
    {
        return Order::selectRaw("
                DATE_FORMAT(orders.created_at, '%Y-%m-%d') as period,
                COUNT(*) as orders,
                COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue
            ")
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            // Exclure les commandes annulées
            ->whereNotIn('orders.status_message', ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'])
            ->groupBy('period')
            ->orderBy('period')
            ->limit(7)
            ->get()
            ->toArray();
    }
}
