<?php

use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);

Route::middleware('guest')->group(function(){
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class);

});

// Route::post('/logout', function(){
//     auth()->logout();
//     return redirect('/');
// })->name('logout');


//     Route::get('/checkout', CheckoutPage::class);
//     Route::get('/my-orders', MyOrdersPage::class);
//     Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
//     Route::get('/success', SuccessPage::class)->name('success');
//     Route::get('/cancel', CancelPage::class)->name(name: 'cancel');

    Route::middleware('auth')->group(function(){
        Route::get('/logout', function(){
            auth()->logout();
            return redirect('/');
        });
    
        Route::get('/checkout', CheckoutPage::class);
        Route::get('/my-orders', MyOrdersPage::class);
        Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
        Route::get('/success', SuccessPage::class)->name('success');
        Route::get('/cancel', CancelPage::class)->name(name: 'cancel');
    }); 
    
 

