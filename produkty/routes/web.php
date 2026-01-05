<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| STREFA PUBLICZNA (Dla Klienta i Gościa)
|--------------------------------------------------------------------------
*/

// Strona główna - lista produktów (dostępna dla każdego)
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products', [ProductController::class, 'index']); // Alias
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// ZMIEŃ/DODAJ w sekcji Koszyka:
Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add'); // Zmiana na POST
Route::patch('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update'); // Nowa trasa
Route::get('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
// NA TO (dodaj where):
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show')
    ->where('product', '[0-9]+'); // Akceptuj tylko cyfry jako ID
/*|--------------------------------------------------------------------------
| STREFA ZALOGOWANEGO UŻYTKOWNIKA (Wspólna)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::post('/checkout', [CartController::class, 'checkout'])
    ->middleware('auth')
    ->name('cart.checkout');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| BACKEND (Dla Pracownika i Admina)
|--------------------------------------------------------------------------
*/
// Grupa tras chroniona logowaniem ORAZ rolą 'employee' (Admin też tu wejdzie dzięki logice w Middleware)
Route::middleware(['auth', 'role:employee'])->group(function () {

    // CRUD Produktów (Tworzenie, Edycja, Usuwanie)
    // Używamy except(['index', 'show']), bo te metody są już publiczne wyżej
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    // Tutaj w przyszłości dodasz zarządzanie zamówieniami (Orders)
});
/*
|--------------------------------------------------------------------------
| PANEL ADMINISTRACYJNY I PRACOWNICZY
|--------------------------------------------------------------------------
*/

// 1. ZARZĄDZANIE UŻYTKOWNIKAMI (Tylko Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/role', [App\Http\Controllers\AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

// 2. ZARZĄDZANIE ZAMÓWIENIAMI (Admin + Pracownik)
// Używamy role:employee, bo nasza logika w middleware puszcza też Admina
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/admin/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});
require __DIR__ . '/auth.php';