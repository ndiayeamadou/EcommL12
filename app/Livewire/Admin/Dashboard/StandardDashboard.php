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

    public function mount()
    {
        $this->currentUser = auth()->user();
    }

    public function changePeriod($period)
    {
        $this->selectedPeriod = $period;
    }

    public function render()
    {
        $dateFilter = $this->getDateFilter();
        
        $data = [
            'selectedPeriod' => $this->selectedPeriod,
            'currentUser' => $this->currentUser,
            'personalStats' => $this->getPersonalStats($dateFilter),
            'teamStats' => $this->getTeamStats($dateFilter),
            'topCustomers' => $this->getTopCustomers($dateFilter),
            'bestSellingProducts' => $this->getBestSellingProducts($dateFilter),
            'outOfStockProducts' => $this->getOutOfStockProducts(),
            'lowStockProducts' => $this->getLowStockProducts(),
            'recentOrders' => $this->getRecentOrders($dateFilter),
            'salesTrend' => $this->getSalesTrend($dateFilter),
        ];

        return view('livewire.admin.dashboard.standard-dashboard', $data)
            ->layout('components.layouts.app');
    }

    private function getDateFilter()
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

    private function getPersonalStats($dateFilter)
    {
        // Commandes créées par l'utilisateur actuel
        $personalOrders = Order::where('created_at', '>=', $dateFilter)
            ->where('agent_id', $this->currentUser->id)
            ->get();

        $personalRevenue = $personalOrders->sum(function($order) {
            return $order->orderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        });

        $personalOrderCount = $personalOrders->count();
        $personalCustomers = $personalOrders->unique('user_id')->count();
        $personalAOV = $personalOrderCount > 0 ? $personalRevenue / $personalOrderCount : 0;

        return [
            'revenue' => $personalRevenue,
            'orders' => $personalOrderCount,
            'customers' => $personalCustomers,
            'aov' => $personalAOV,
            'today_orders' => Order::whereDate('created_at', Carbon::today())
                ->where('agent_id', $this->currentUser->id)
                ->count(),
        ];
    }

    private function getTeamStats($dateFilter)
    {
        // Statistiques de toute l'équipe (tous les agents)
        $teamRevenue = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            ->sum(DB::raw('order_items.price * order_items.quantity')) ?? 0;

        $teamOrders = Order::where('created_at', '>=', $dateFilter)->count();
        $teamCustomers = Order::where('created_at', '>=', $dateFilter)->distinct('user_id')->count('user_id');

        return [
            'revenue' => $teamRevenue,
            'orders' => $teamOrders,
            'customers' => $teamCustomers,
            'products' => Product::count(),
            'out_of_stock' => Product::where('in_stock', false)->count(),
            'low_stock' => Product::where('stock_quantity', '<=', 10)->where('manage_stock', true)->count(),
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
            ->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function getBestSellingProducts($dateFilter)
    {
        return Product::select('products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as units_sold')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $dateFilter)
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->orderByDesc('units_sold')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function getOutOfStockProducts()
    {
        //return Product::where('in_stock', false)
        return Product::where('stock_quantity', '<=', 0)
            ->select('name', 'sku', 'price', 'stock_quantity')
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getLowStockProducts()
    {
        return Product::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->where('manage_stock', true)
            ->select('name', 'sku', 'price', 'stock_quantity')
            ->orderBy('stock_quantity')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getRecentOrders($dateFilter)
    {
        return Order::with(['user', 'orderItems.product'])
            ->where('created_at', '>=', $dateFilter)
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
                ];
            })
            ->toArray();
    }

    private function getSalesTrend($dateFilter)
    {
        return Order::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m-%d') as period,
                COUNT(*) as orders,
                COALESCE(SUM((SELECT SUM(price * quantity) FROM order_items WHERE order_items.order_id = orders.id)), 0) as revenue
            ")
            ->where('created_at', '>=', $dateFilter)
            ->groupBy('period')
            ->orderBy('period')
            ->limit(7)
            ->get()
            ->toArray();
    }
}
