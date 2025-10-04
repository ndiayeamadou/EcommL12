<?php

use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Products\ProductCreate;
use App\Livewire\Admin\Products\ProductsIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'adminSys'])
    ->name('dashboard'); */

Route::middleware(['auth', 'adminSys'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'adminSys'])->group(function() {
    Route::get('categories', CategoryManager::class)->name('categories');
    Route::get('produits', ProductsIndex::class)->name('products.index');
    Route::get('produit/creation', ProductCreate::class)->name('products.create');
    Route::get('produit/{product}/edit', App\Livewire\Admin\Products\ProductEdit::class)->name('products.edit');
    Route::get('produit/{product}/show', App\Livewire\Admin\Products\ProductEdit::class)->name('products.show');
});

require __DIR__.'/auth.php';
require __DIR__.'/in/admin.php';
