<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 1. Wyświetlanie koszyka
    public function index()
    {
        // Pobieramy koszyk z sesji (jeśli pusty, to pusta tablica)
        $cart = session()->get('cart', []);

        // Jeśli koszyk nie jest pusty, pobieramy szczegóły produktów z bazy
        // (W sesji mamy tylko ID i ilość, potrzebujemy nazwy i ceny)
        $products = [];
        $totalSum = 0;

        if (!empty($cart)) {
            // Pobieramy produkty, których ID są w koszyku
            $productsInDb = Product::whereIn('id', array_keys($cart))->get();

            foreach ($productsInDb as $product) {
                $quantity = $cart[$product->id];
                $product->quantity = $quantity; // "Doklejamy" ilość do obiektu
                $product->subtotal = $product->price * $quantity; // Suma częściowa

                $products[] = $product;
                $totalSum += $product->subtotal;
            }
        }

        return view('cart.index', compact('products', 'totalSum'));
    }

    // 2. Dodawanie do koszyka
    public function addToCart(Request $request, Product $product)
    {
        // Walidacja ilości (domyślnie 1 jeśli nie podano)
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity < 1)
            $quantity = 1;

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $quantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', "Dodano $quantity szt. produktu do koszyka!");
    }

    public function updateCart(Request $request, $id)
    {
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity < 1)
            $quantity = 1;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id] = $quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Zaktualizowano ilość.');
    }
    // 3. Usuwanie z koszyka
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produkt usunięty z koszyka.');
    }

    // 4. Finalizacja zamówienia (Zapis do bazy)
    public function checkout(Request $request)
    {
        if (!Auth::check())
            return redirect()->route('login');

        // Łączymy pola adresu w jeden string do bazy
        $fullAddress = sprintf(
            "%s, %s %s, %s",
            $request->input('street'),
            $request->input('zip'),
            $request->input('city'),
            $request->input('phone')
        );
        // Musi być zalogowany (Middleware to załatwi, ale sprawdzamy dla pewności)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Musisz się zalogować, aby złożyć zamówienie.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Twój koszyk jest pusty.');
        }

        // DB::transaction zapewnia, że albo wykonają się wszystkie zapytania, albo żadne
        // (np. nie stworzy się puste zamówienie jeśli wystąpi błąd przy produktach)
        try {
            DB::transaction(function () use ($cart, $fullAddress) {
                $user = Auth::user();
                $totalPrice = 0;

                // 1. Tworzymy nagłówek zamówienia
                // Pobieramy status "Nowe" (lub tworzymy jeśli nie istnieje)
                $status = OrderStatus::firstOrCreate(['name' => 'Nowe']);

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_status_id' => $status->id,
                    'total_price' => 0, // Zaktualizujemy za chwilę
                    'shipping_address' => $fullAddress->input('address', 'Adres domyślny klienta'), // Uproszczenie
                ]);

                // 2. Tworzymy pozycje zamówienia
                $productsInDb = Product::whereIn('id', array_keys($cart))->get();

                foreach ($productsInDb as $product) {
                    $quantity = $cart[$product->id];
                    $price = $product->price; // Cena w momencie zakupu!

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalPrice += $price * $quantity;
                }

                // 3. Aktualizujemy łączną kwotę zamówienia
                $order->update(['total_price' => $totalPrice]);

                // 4. Czyścimy koszyk
                session()->forget('cart');
            });
            return redirect()->route('products.index')->with('success', 'Zamówienie złożone!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}