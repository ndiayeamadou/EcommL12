<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OrdersDashboard extends Component
{
    public string $dateRange = '30';
    public string $selectedPeriod = 'daily';
    
    // Statistiques principales
    public int $totalOrders = 0;
    public float $totalRevenue = 0;
    public float $averageOrderValue = 0;
    public int $totalCustomers = 0;
    
    // Données pour les graphiques
    public array $revenueData = [];
    public array $ordersData = [];
    public array $topProducts = [];
    public array $ordersByStatus = [];
    public array $recentOrders = [];
    
    // Comparaison avec la période précédente
    public float $revenueGrowth = 0;
    public float $ordersGrowth = 0;
    public float $customersGrowth = 0;

    protected array $queryString = [
        'dateRange' => ['except' => '30'],
        'selectedPeriod' => ['except' => 'daily'],
    ];

    public function mount(): void
    {
        $this->loadDashboardData();
    }

    public function updatedDateRange(): void
    {
        $this->validateDateRange();
        $this->loadDashboardData();
    }

    public function updatedSelectedPeriod(): void
    {
        $this->validateSelectedPeriod();
        $this->loadDashboardData();
    }

    private function validateDateRange(): void
    {
        if (!in_array($this->dateRange, ['7', '30', '90', '365'])) {
            $this->dateRange = '30';
        }
    }

    private function validateSelectedPeriod(): void
    {
        if (!in_array($this->selectedPeriod, ['daily', 'weekly', 'monthly'])) {
            $this->selectedPeriod = 'daily';
        }
    }

    public function loadDashboardData(): void
    {
        try {
            $days = (int) $this->dateRange;
            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            // Période précédente pour comparaison
            $previousStartDate = Carbon::now()->subDays($days * 2)->startOfDay();
            $previousEndDate = Carbon::now()->subDays($days)->endOfDay();

            $this->loadMainStats($startDate, $endDate, $previousStartDate, $previousEndDate);
            $this->loadChartData($startDate, $endDate);
            $this->loadTopProducts($startDate, $endDate);
            $this->loadOrdersByStatus($startDate, $endDate);
            $this->loadRecentOrders();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'text' => 'Erreur lors du chargement des données: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 500
            ]);
            
            $this->resetStats();
        }
    }

    private function resetStats(): void
    {
        $this->totalOrders = 0;
        $this->totalRevenue = 0;
        $this->averageOrderValue = 0;
        $this->totalCustomers = 0;
        $this->revenueGrowth = 0;
        $this->ordersGrowth = 0;
        $this->customersGrowth = 0;
        $this->revenueData = [];
        $this->ordersData = [];
        $this->topProducts = [];
        $this->ordersByStatus = [];
        $this->recentOrders = [];
    }

    private function loadMainStats(Carbon $startDate, Carbon $endDate, Carbon $previousStartDate, Carbon $previousEndDate): void
    {
        // Période actuelle
        $currentOrdersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $this->totalOrders = $currentOrdersQuery->count();
        
        $currentRevenue = $this->calculateRevenue($startDate, $endDate);
        $this->totalRevenue = $currentRevenue;
        
        $this->averageOrderValue = $this->totalOrders > 0 ? round($this->totalRevenue / $this->totalOrders, 2) : 0;
        
        $this->totalCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count();

        // Période précédente pour comparaison
        $previousOrders = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $previousRevenue = $this->calculateRevenue($previousStartDate, $previousEndDate);
        $previousCustomers = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->distinct('user_id')
            ->count();

        // Calcul des pourcentages de croissance
        $this->ordersGrowth = $this->calculateGrowthPercentage($this->totalOrders, $previousOrders);
        $this->revenueGrowth = $this->calculateGrowthPercentage($this->totalRevenue, $previousRevenue);
        $this->customersGrowth = $this->calculateGrowthPercentage($this->totalCustomers, $previousCustomers);
    }

    private function calculateRevenue(Carbon $startDate, Carbon $endDate): float
    {
        return (float) OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('CAST(order_items.quantity AS DECIMAL(10,2)) * CAST(order_items.price AS DECIMAL(10,2))'));
    }

    private function calculateGrowthPercentage(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function loadChartData(Carbon $startDate, Carbon $endDate): void
    {
        $dateFormat = $this->getDateFormat();
        $groupByFormat = $this->getGroupByFormat();

        // Données de revenus par période
        $revenueQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(orders.created_at, ?) as period", [$dateFormat])
            ->selectRaw('SUM(CAST(order_items.quantity AS DECIMAL(10,2)) * CAST(order_items.price AS DECIMAL(10,2))) as revenue')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $this->revenueData = $revenueQuery->map(function ($item) {
            return [
                'period' => $item->period,
                'revenue' => (float) $item->revenue
            ];
        })->toArray();

        // Données de commandes par période
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, ?) as period", [$dateFormat])
            ->selectRaw('COUNT(*) as count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $this->ordersData = $ordersQuery->map(function ($item) {
            return [
                'period' => $item->period,
                'count' => (int) $item->count
            ];
        })->toArray();
    }

    private function getDateFormat(): string
    {
        return match ($this->selectedPeriod) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d'
        };
    }

    private function getGroupByFormat(): string
    {
        return match ($this->selectedPeriod) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'YEARWEEK(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            default => 'DATE(created_at)'
        };
    }

    private function loadTopProducts(Carbon $startDate, Carbon $endDate): void
    {
        $topProductsQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select([
                'products.id',
                'products.name',
                'products.price'
            ])
            ->selectRaw('SUM(CAST(order_items.quantity AS DECIMAL(10,2))) as total_quantity')
            ->selectRaw('SUM(CAST(order_items.quantity AS DECIMAL(10,2)) * CAST(order_items.price AS DECIMAL(10,2))) as total_revenue')
            ->groupBy(['products.id', 'products.name', 'products.price'])
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $this->topProducts = $topProductsQuery->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'total_quantity' => (float) $product->total_quantity,
                'total_revenue' => (float) $product->total_revenue
            ];
        })->toArray();
    }

    private function loadOrdersByStatus(Carbon $startDate, Carbon $endDate): void
    {
        $ordersByStatusQuery = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status_message')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status_message')
            ->orderByDesc('count')
            ->get();

        $this->ordersByStatus = $ordersByStatusQuery->map(function ($status) {
            return [
                'status_message' => $status->status_message ?? 'Non défini',
                'count' => (int) $status->count
            ];
        })->toArray();
    }

    private function loadRecentOrders(): void
    {
        $recentOrdersQuery = Order::with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->recentOrders = $recentOrdersQuery->map(function ($order) {
            $total = $order->orderItems->sum(function ($item) {
                return (float) $item->quantity * (float) $item->price;
            });

            return [
                'id' => $order->id,
                'tracking_no' => $order->tracking_no,
                'customer_name' => $order->fullname ?? 'Client inconnu',
                'total' => $total,
                'status' => $order->status_message ?? 'Non défini',
                'created_at' => $order->created_at->toISOString(),
                'items_count' => $order->orderItems->count(),
            ];
        })->toArray();
    }

    public function refreshData(): void
    {
        $this->loadDashboardData();
        
        $this->dispatch('notify', [
            'text' => 'Données actualisées avec succès',
            'type' => 'success',
            'status' => 200
        ]);
    }

    public function setDateRange(string $range): void
    {
        if (in_array($range, ['7', '30', '90', '365'])) {
            $this->dateRange = $range;
            $this->loadDashboardData();
        }
    }

    public function setPeriod(string $period): void
    {
        if (in_array($period, ['daily', 'weekly', 'monthly'])) {
            $this->selectedPeriod = $period;
            $this->loadDashboardData();
        }
    }

    // Accesseurs pour les propriétés calculées
    public function getFormattedTotalRevenueProperty(): string
    {
        return number_format($this->totalRevenue, 0, ',', ' ') . ' F';
    }

    public function getFormattedAverageOrderValueProperty(): string
    {
        return number_format($this->averageOrderValue, 0, ',', ' ') . ' F';
    }

    public function getRevenueGrowthColorProperty(): string
    {
        return $this->revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600';
    }

    public function getOrdersGrowthColorProperty(): string
    {
        return $this->ordersGrowth >= 0 ? 'text-green-600' : 'text-red-600';
    }

    public function getCustomersGrowthColorProperty(): string
    {
        return $this->customersGrowth >= 0 ? 'text-green-600' : 'text-red-600';
    }

    public function render()
    {
        return view('livewire.admin.orders.orders-dashboard');
    }
}
