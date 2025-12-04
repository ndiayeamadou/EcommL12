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

class SuperAdminDashboard extends Component
{
    public $selectedPeriod = 'month';

    public function mount(): void
    {
        if (!auth()->user()->can('manage_system_users')) {
            abort(403, 'Accès refusé. Permissions insuffisantes.');
        }
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
            'stats' => $this->getMainStats($dateFilter),
            'revenueByCategory' => $this->getRevenueByCategory($dateFilter),
            'topCustomers' => $this->getTopCustomers($dateFilter),
            'bestSellingProducts' => $this->getBestSellingProducts($dateFilter),
            'outOfStockProducts' => $this->getOutOfStockProducts(),
            'lowStockProducts' => $this->getLowStockProducts(),
            'conversionMetrics' => $this->getConversionMetrics($dateFilter),
            'salesTrend' => $this->getSalesTrend($dateFilter),
        ];

        return view('livewire.admin.dashboard.super-admin-dashboard', $data)
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

    /**
     * Liste des statuts considérés comme annulés
     */
    private function getCancelledStatuses(): array
    {
        return ['Annulé', 'Annulée', 'Cancelled', 'Refused', 'Refusé'];
    }

    private function getMainStats($dateFilter)
    {
        // Calcul du CA (exclure les commandes annulées)
        $revenue = Order::where('orders.created_at', '>=', $dateFilter)
            ->whereNotIn('orders.status_message', $this->getCancelledStatuses())
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum(DB::raw('order_items.price * order_items.quantity')) ?? 0;

        // Nombre de commandes (exclure les annulées)
        $orders = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', $this->getCancelledStatuses())
            ->count();

        // Nombre de clients uniques (exclure les commandes annulées)
        $customers = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', $this->getCancelledStatuses())
            ->distinct('user_id')
            ->count('user_id');

        $aov = $orders > 0 ? $revenue / $orders : 0;

        return [
            'revenue' => $revenue,
            'orders' => $orders,
            'customers' => $customers,
            'aov' => $aov,
            'products' => Product::count(),
            'categories' => Category::count(),
            'brands' => Brand::count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 10)
                ->where('manage_stock', true)
                ->count(),
        ];
    }

    private function getRevenueByCategory($dateFilter)
    {
        return Category::select('categories.id', 'categories.name', 'categories.color')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($dateFilter) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.created_at', '>=', $dateFilter)
                     ->whereNotIn('orders.status_message', $this->getCancelledStatuses());
            })
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('revenue')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function getTopCustomers($dateFilter)
    {
        return User::select('users.firstname', 'users.lastname', 'users.email')
            ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_spent')
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            ->whereNotIn('orders.status_message', $this->getCancelledStatuses())
            ->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getBestSellingProducts($dateFilter)
    {
        return Product::select('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as units_sold')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($dateFilter) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.created_at', '>=', $dateFilter)
                     ->whereNotIn('orders.status_message', $this->getCancelledStatuses());
            })
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock_quantity')
            ->orderByDesc('units_sold')
            ->limit(8)
            ->get()
            ->toArray();
    }

    private function getOutOfStockProducts()
    {
        return Product::where('stock_quantity', '<=', 0)
            ->select('id', 'name', 'sku', 'price', 'stock_quantity')
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getLowStockProducts()
    {
        return Product::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->where('manage_stock', true)
            ->select('id', 'name', 'sku', 'price', 'stock_quantity')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getConversionMetrics($dateFilter)
    {
        // Pour les conversions, on exclut aussi les commandes annulées
        $totalVisitors = User::where('created_at', '>=', $dateFilter)->count();
        
        // Commandes non annulées uniquement
        $totalOrders = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', $this->getCancelledStatuses())
            ->count();
            
        // Clients ayant effectué des commandes non annulées
        $totalCustomers = Order::where('created_at', '>=', $dateFilter)
            ->whereNotIn('status_message', $this->getCancelledStatuses())
            ->distinct('user_id')
            ->count('user_id');

        return [
            'visitor_to_customer' => $totalVisitors > 0 ? round(($totalCustomers / $totalVisitors) * 100, 1) : 0,
            'cart_to_order' => $totalCustomers > 0 ? round(($totalOrders / $totalCustomers) * 100, 1) : 0,
        ];
    }

    private function getSalesTrend($dateFilter)
    {
        return Order::selectRaw("
                DATE_FORMAT(orders.created_at, '%Y-%m-%d') as period,
                COUNT(*) as orders,
                COALESCE(SUM(order_items.price * order_items.quantity), 0) as revenue
            ")
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', $dateFilter)
            ->whereNotIn('orders.status_message', $this->getCancelledStatuses())
            ->groupBy('period')
            ->orderBy('period')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
