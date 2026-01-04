<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Allergen;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tworzymy Administratora
        \App\Models\User::factory()->create([
            'name' => 'Admin Główny',
            'email' => 'admin@sklep.pl',
            'password' => bcrypt('password'), // Hasło: password
            'role' => 'admin',
        ]);

        // 2. Tworzymy Pracownika
        \App\Models\User::factory()->create([
            'name' => 'Jan Pracownik',
            'email' => 'pracownik@sklep.pl',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        // 3. Tworzymy Klienta
        \App\Models\User::factory()->create([
            'name' => 'Anna Klientka',
            'email' => 'klient@sklep.pl',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        // 1. Tworzymy pulę 50 Marek
        $brands = Brand::factory(50)->create();

        // 2. Tworzymy pulę 100 Kategorii
        $categories = Category::factory(100)->create();

        // 3. Tworzymy pulę 50 Alergenów
        $allergens = Allergen::factory(50)->create();

        // 4. Tworzymy 1000 Produktów KORZYSTAJĄC Z PULI
        // Ważne: Używamy metody recycle(), która mówi Laravelowi:
        // "Jeśli potrzebujesz marki lub kategorii, weź jedną z tych, które już stworzyłem, nie rób nowych!"

        Product::factory(1000)
            ->recycle($brands)      // Użyj istniejących marek
            ->recycle($categories)  // Użyj istniejących kategorii
            ->create();             // Stwórz produkty

        // 5. Przypisujemy alergeny (to pozostaje bez zmian)
        Product::all()->each(function ($product) use ($allergens) {
            $product->allergens()->attach(
                $allergens->random(rand(0, 3))->pluck('id')->toArray()
            );
        });
    }
}