<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_added_to_cart()
    {
        // 1. Tworzymy produkt
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id
        ]);

        // 2. Wysyłamy żądanie POST (tak jak formularz)
        // Dodajemy 2 sztuki
        $response = $this->post(route('cart.add', $product->id), [
            'quantity' => 2
        ]);

        // 3. Sprawdzamy czy przekierowało nas z powrotem (standardowe zachowanie)
        $response->assertStatus(302);

        // 4. Sprawdzamy czy w sesji jest ten produkt w ilości 2
        $response->assertSessionHas('cart', function ($cart) use ($product) {
            return $cart[$product->id] === 2;
        });
    }
}