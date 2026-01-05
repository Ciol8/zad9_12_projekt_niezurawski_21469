<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_place_order()
    {
        // 1. Dane
        $user = User::factory()->create();
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'price' => 100.00,
            'brand_id' => $brand->id,
            'category_id' => $category->id
        ]);

        // 2. Symulujemy koszyk w sesji
        // Użytkownik ma 1 produkt w koszyku
        session()->put('cart', [
            $product->id => 1
        ]);

        // 3. Wysyłamy formularz zamówienia
        $response = $this->actingAs($user)->post(route('cart.checkout'), [
            'street' => 'Polna 1',
            'zip' => '00-001',
            'city' => 'Warszawa',
            'phone' => '123456789',
        ]);

        // 4. Asercje
        // Czy przekierowało na listę produktów z sukcesem?
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        // Czy zamówienie powstało w bazie?
        $this->assertDatabaseCount('orders', 1);

        // Czy koszyk jest pusty?
        $this->assertEmpty(session('cart'));
    }
}