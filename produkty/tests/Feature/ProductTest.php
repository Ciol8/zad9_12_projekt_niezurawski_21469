<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // To sprawia, że baza czyści się po każdym teście
    use RefreshDatabase;

    public function test_product_can_be_created()
    {
        // 1. Przygotowanie danych (Arrange)
        // Musimy stworzyć markę i kategorię, bo produkt ich wymaga
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // 2. Działanie (Act)
        $product = Product::create([
            'name' => 'Testowy Produkt',
            'price' => 100.50,
            'kcal_per_100g' => 250,
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        // 3. Sprawdzenie (Assert)
        // Sprawdzamy czy w bazie jest 1 produkt
        $this->assertDatabaseCount('products', 1);

        // Sprawdzamy czy ten produkt ma taką nazwę
        $this->assertEquals('Testowy Produkt', $product->name);
    }

    public function test_admin_can_see_create_product_page()
    {
        // Tworzymy admina
        $admin = User::factory()->create(['role' => 'admin']);

        // Udajemy, że jesteśmy zalogowani jako admin i wchodzimy na stronę
        $response = $this->actingAs($admin)->get(route('products.create'));

        // Oczekujemy statusu 200 (OK)
        $response->assertStatus(200);
    }
}