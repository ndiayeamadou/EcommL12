<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EcommerceDashboard extends Component
{
    public $totalProducts;
    public $totalOrders;
    public $totalRevenue;
    public $totalCustomers;
    public $topProducts;
    public $topCustomers;
    public $outOfStockProducts;
    public $recentOrders;
    public $categoryStats;
    public $monthlyRevenue;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Statistiques générales
        $this->totalProducts = Product::active()->count();
        $this->totalOrders = Order::count();
        $this->totalCustomers = User::where('type', 0)->count();
        
        // Revenus totaux
        $this->totalRevenue = OrderItem::sum(DB::raw('quantity * price'));
        
        // Produits les plus vendus
        $this->topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product.primaryImage')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
            
        // Clients qui commandent le plus
        $this->topCustomers = Order::select('user_id', DB::raw('COUNT(*) as order_count'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get();
            
        // Produits en rupture de stock
        $this->outOfStockProducts = Product::where('stock_quantity', '<=', 5)
            ->with('primaryImage')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();
            
        // Commandes récentes
        $this->recentOrders = Order::with('user', 'orderItems.product')
            ->latest()
            ->limit(5)
            ->get();
            
        // Statistiques par catégorie
        $this->categoryStats = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->limit(6)
            ->get();
            
        // Revenus mensuels (derniers 6 mois)
        $this->monthlyRevenue = OrderItem::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(quantity * price) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.ecommerce-dashboard');
    }
}
