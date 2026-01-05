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
<h3>Opinie klientów</h3>

{{-- Formularz dodawania (tylko dla zalogowanych) --}}
@auth
    <form action="{{ route('reviews.store', $product) }}" method="POST" style="background: #f9f9f9; padding: 15px; margin-bottom: 20px;">
        @csrf
        <h4>Dodaj swoją opinię</h4>
        <div class="form-group">
            <label>Ocena:</label>
            <select name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                <option value="3">⭐⭐⭐ (3/5)</option>
                <option value="2">⭐⭐ (2/5)</option>
                <option value="1">⭐ (1/5)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Treść:</label>
            <textarea name="content" rows="3" required class="form-control"></textarea>
        </div>
        <button type="submit" class="btn">Wyślij opinię</button>
    </form>
@endauth

{{-- Lista opinii --}}
@foreach($product->reviews as $review)
    <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
        <strong>{{ $review->user->name }}</strong> 
        <span style="color: gold;">{{ str_repeat('★', $review->rating) }}</span>
        <p>{{ $review->content }}</p>
        <small style="color: gray;">{{ $review->created_at->format('d.m.Y H:i') }}</small>
    </div>
@endforeach
        <hr>

        <form action="{{ route('cart.add', $product) }}" method="POST"
            style="display: flex; align-items: center; gap: 10px;">
            @csrf
            <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 5px;">
            <button type="submit" class="btn" style="background: #007bff; color: white;">🛒 Dodaj do koszyka</button>
        </form>
    </div>
@endsection