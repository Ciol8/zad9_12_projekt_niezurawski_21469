<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Allergen;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Metoda publiczna (dostępna dla wszystkich w routes/web.php)
    public function index()
    {
        $products = Product::with(['brand', 'category', 'allergens'])->paginate(20);
        return view('products.index', compact('products'));
    }
    public function show(Product $product)
    {
        // Ładujemy relacje, żeby wyświetlić markę, kategorię i opinie
        $product->load(['brand', 'category', 'allergens', 'reviews.user']);

        return view('products.show', compact('product'));
    }
    // Metody chronione (dostępne tylko dla pracownika/admina w routes/web.php)
    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();
        return view('products.create', compact('brands', 'categories', 'allergens'));
    }

    public function store(Request $request)
    {
        // Definiujemy komunikaty
        $messages = [
            'price.min' => 'Cena nie może być ujemna!',
            'kcal_per_100g.min' => 'Wartość kalorii nie może być ujemna!',
            'required' => 'To pole jest wymagane.',
            'numeric' => 'Wartość musi być liczbą.',
            'integer' => 'Wartość musi być liczbą całkowitą.',
        ];

        // Wykonujemy walidację TYLKO RAZ
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kcal_per_100g' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array',
            'allergens.*' => 'exists:allergens,id',
        ], $messages);

        // Tworzymy produkt
        $product = Product::create($validated);

        // Relacja N:M
        if (isset($validated['allergens'])) {
            $product->allergens()->sync($validated['allergens']);
        }

        return redirect()->route('products.index')->with('success', 'Produkt dodany!');
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();
        return view('products.edit', compact('product', 'brands', 'categories', 'allergens'));
    }

    public function update(Request $request, Product $product)
    {
        $messages = [
            'price.min' => 'Cena nie może być ujemna!',
            'kcal_per_100g.min' => 'Wartość kalorii nie może być ujemna!',
            'required' => 'To pole jest wymagane.',
        ];

        // Walidacja raz
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kcal_per_100g' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array',
            'allergens.*' => 'exists:allergens,id',
        ], $messages);

        $product->update($validated);

        // Synchronizacja
        $product->allergens()->sync($request->input('allergens', []));

        return redirect()->route('products.index')->with('success', 'Produkt zaktualizowany!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produkt usunięty!');
    }
}