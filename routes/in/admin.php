<?php

use App\Livewire\Admin\Company\CompanyIndex;
use App\Livewire\Admin\Customers\CustomersManager;
use App\Livewire\Admin\Dashboard\EcommerceDashboard;
use App\Livewire\Admin\Orders\OrdersDashboard;
use App\Livewire\Admin\Pos\PosInterface;
use App\Livewire\Admin\Pos\SalesList;
use App\Livewire\Admin\Pos\SalesManager;
use App\Livewire\Admin\Users\AdminUsersManager;
use Illuminate\Support\Facades\Route;

// Routes POS (Point of Sale)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'adminSys'])->group(function () {
    /* Company */
    Route::get('/company', CompanyIndex::class)->name('company');

    Route::prefix('pos')->name('pos.')->group(function () {
        // Interface principale POS
        Route::get('/interface', PosInterface::class)->name('interface');
        Route::get('/', SalesManager::class)->name('sales');

        Route::get('/checkout', \App\Livewire\Admin\Checkout\CheckoutPage::class)->name('checkout');
        
        // Historique des ventes
        //Route::get('/sales', SalesList::class)->name('sales');
        
        // Impression de reçu
        /* Route::get('/receipt/{sale}', function (Sale $sale) {
            return view('pos.receipt', compact('sale'));
        })->name('receipt'); */
        
        // Export des ventes
        Route::get('/export', function () {
            // Logique d'export CSV/PDF
            return redirect()->route('pos.sales')->with('message', 'Export en cours...');
        })->name('export');
        
        // API endpoints pour scanner de code-barres
        /* Route::post('/scan', function (Request $request) {
            $barcode = $request->input('barcode');
            $product = Product::where('sku', $barcode)->first();
            
            return response()->json([
                'success' => $product ? true : false,
                'product' => $product,
                'message' => $product ? 'Produit trouvé' : 'Produit non trouvé'
            ]);
        })->name('scan'); */
    });

    /* Commandes - Orders */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Orders\OrdersManager::class)->name('index');
        Route::get('/{order}/details', \App\Livewire\Admin\Orders\OrderDetails::class)->name('details');
        Route::get('/dashboard', OrdersDashboard::class)->name('dashboard');
    });

    /* Customers */
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', CustomersManager::class)->name('index');
        //Route::get('/details', CustomersManager::class)->name('show');
    });

    /* Customers */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', AdminUsersManager::class)->name('index');
    });


    Route::get('/standard-dashboard', \App\Livewire\Admin\Dashboard\StandardDashboard::class)->middleware('auth')->name('standard-dashboard');
    Route::get('/super-dashboard', \App\Livewire\Admin\Dashboard\SuperAdminDashboard::class)->middleware('auth')->name('super-admin-dashboard');

});

