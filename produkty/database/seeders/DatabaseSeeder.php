<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Allergen;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Podstawowe dane produktów (Tak jak wcześniej)
        $brands = Brand::factory(50)->create();
        $categories = Category::factory(100)->create();
        $allergens = Allergen::factory(50)->create();

        $products = Product::factory(1000)
            ->recycle($brands)
            ->recycle($categories)
            ->create();

        $products->each(function ($product) use ($allergens) {
            $product->allergens()->attach($allergens->random(rand(0, 3))->pluck('id')->toArray());
        });

        // 2. Statusy zamówień (Stała lista)
        $statuses = collect(['Nowe', 'W realizacji', 'Wysłane', 'Dostarczone', 'Anulowane']);
        $statusModels = $statuses->map(fn($status) => OrderStatus::create(['name' => $status]));

        // 3. Użytkownicy (Role)
        User::factory()->create([
            'name' => 'Admin Główny',
            'email' => 'admin@sklep.pl',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Jan Pracownik',
            'email' => 'pracownik@sklep.pl',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Anna Klientka',
            'email' => 'klient@sklep.pl',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        // Dodatkowo 50 losowych klientów
        $users = User::factory(50)->create(['role' => 'client']);

        // 4. Zamówienia i Pozycje
        // Dla każdego z 50 klientów stwórzmy 1-5 zamówień
        $users->each(function ($user) use ($products, $statusModels) {
            $ordersCount = rand(1, 5);

            Order::factory($ordersCount)->create([
                'user_id' => $user->id,
                'order_status_id' => $statusModels->random()->id,
            ])->each(function ($order) use ($products) {
                // Do każdego zamówienia dodaj 1-5 produktów
                $totalPrice = 0;

                for ($i = 0; $i < rand(1, 5); $i++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalPrice += $quantity * $price;
                }

                // Aktualizujemy sumę zamówienia
                $order->update(['total_price' => $totalPrice]);
            });
        });

        // 5. Opinie (Reviews)
        // Stwórz 200 losowych opinii
        Review::factory(200)->make()->each(function ($review) use ($users, $products) {
            $review->user_id = $users->random()->id;
            $review->product_id = $products->random()->id;
            $review->save();
        });
    }
}