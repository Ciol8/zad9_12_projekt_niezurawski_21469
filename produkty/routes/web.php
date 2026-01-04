<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| STREFA PUBLICZNA (Dla Klienta i Gościa)
|--------------------------------------------------------------------------
*/

// Strona główna - lista produktów (dostępna dla każdego)
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products', [ProductController::class, 'index']); // Alias

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

require __DIR__ . '/auth.php';