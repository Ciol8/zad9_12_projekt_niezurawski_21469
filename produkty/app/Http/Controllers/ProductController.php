<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Allergen;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Paginacja + Eager Loading (zapobiega problemowi N+1 zapytań)
        $products = Product::with(['brand', 'category', 'allergens'])->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Pobieramy dane do selectów
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $allergens = Allergen::orderBy('name')->get();
        return view('products.create', compact('brands', 'categories', 'allergens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kcal_per_100g' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array', // Tablica ID z checkboxów
            'allergens.*' => 'exists:allergens,id',
        ]);

        $product = Product::create($validated);

        // W metodzie store() oraz update():

        $messages = [
            'price.min' => 'Cena nie może być ujemna!',
            'kcal_per_100g.min' => 'Wartość kalorii nie może być ujemna!',
            'required' => 'To pole jest wymagane.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0', // min:0 blokuje ujemne
            'kcal_per_100g' => 'required|integer|min:0', // min:0 blokuje ujemne
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array',
            'allergens.*' => 'exists:allergens,id',
        ], $messages); // <-- Przekazujemy komunikaty tutaj

        // Synchronizacja relacji N:M
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kcal_per_100g' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array',
        ]);

        // W metodzie store() oraz update():

        $messages = [
            'price.min' => 'Cena nie może być ujemna!',
            'kcal_per_100g.min' => 'Wartość kalorii nie może być ujemna!',
            'required' => 'To pole jest wymagane.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0', // min:0 blokuje ujemne
            'kcal_per_100g' => 'required|integer|min:0', // min:0 blokuje ujemne
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'allergens' => 'array',
            'allergens.*' => 'exists:allergens,id',
        ], $messages); // <-- Przekazujemy komunikaty tutaj

        $product->update($validated);

        // Synchronizacja N:M (jeśli pusta tablica, usuwa wszystkie powiązania)
        $product->allergens()->sync($request->input('allergens', []));

        return redirect()->route('products.index')->with('success', 'Produkt zaktualizowany!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produkt usunięty!');
    }
}