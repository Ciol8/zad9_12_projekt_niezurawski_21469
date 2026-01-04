@extends('layout')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('products.index') }}" class="btn">« Powrót do listy</a>
    </div>

    <div style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h1 style="margin-top: 0;">{{ $product->name }}</h1>
        <p style="color: gray;">Kategoria: {{ $product->category->name }} | Marka: {{ $product->brand->name }}</p>

        <h2 style="color: #28a745;">{{ number_format($product->price, 2) }} PLN</h2>
        <p><strong>Kalorie:</strong> {{ $product->kcal_per_100g }} kcal / 100g</p>

        <hr>

        <h3>Alergeny:</h3>
        @if($product->allergens->count() > 0)
            @foreach($product->allergens as $allergen)
                <span style="background: #eee; padding: 5px 10px; border-radius: 15px; margin-right: 5px;">
                    {{ $allergen->name }} ({{ $allergen->severity }})
                </span>
            @endforeach
        @else
            <p>Brak alergenów.</p>
        @endif

        <hr>

        {{-- Tutaj w przyszłości dodasz przycisk "Dodaj do koszyka" --}}
        <button class="btn" style="background: #007bff; color: white;">Dodaj do koszyka</button>
    </div>
@endsection