@extends('layout')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('products.index') }}" class="btn">« Powrót do listy</a>
    </div>

    <div style="background: white; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px;">
        {{-- H1: Tytuł główny --}}
        <h1 style="margin-top: 0;">{{ $product->name }}</h1>
        <p style="font-weight: 500;">Kategoria: {{ $product->category->name }} | Marka: {{ $product->brand->name }}</p>

        <div style="font-size: 1.5em; color: var(--success); font-weight: bold; margin: 10px 0;">
            {{ number_format($product->price, 2) }} PLN
        </div>
        <p><strong>Kalorie:</strong> {{ $product->kcal_per_100g }} kcal / 100g</p>

        {{-- Formularz dodawania do koszyka --}}
        <form action="{{ route('cart.add', $product) }}" method="POST"
            style="display: flex; align-items: center; gap: 10px; margin-top: 20px;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label for="quantity" class="visually-hidden">Ilość:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" style="width: 70px; padding: 8px;">
            <button type="submit" class="btn btn-primary">🛒 Dodaj do koszyka</button>
        </form>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #dee2e6;">

        {{-- H2: Sekcje podrzędne (Naprawa błędu hierarchii) --}}
        <h2>Alergeny</h2>
        @if($product->allergens->count() > 0)
            @foreach($product->allergens as $allergen)
                @php
                    // Logika kolorów (skrócona dla czytelności kodu tutaj)
                    $bg = '#6c757d';
                    $col = '#fff';
                    if ($allergen->severity == 'low')
                        $bg = '#198754';
                    if ($allergen->severity == 'medium') {
                        $bg = '#ffc107';
                        $col = '#000';
                    }
                    if ($allergen->severity == 'high')
                        $bg = '#dc3545';
                    if ($allergen->severity == 'deadly')
                        $bg = '#800080';
                @endphp
                <span
                    style="background: {{ $bg }}; color: {{ $col }}; padding: 5px 10px; border-radius: 15px; margin-right: 5px; display: inline-block;">
                    {{ $allergen->name }} ({{ $allergen->severity }})
                </span>
            @endforeach
        @else
            <p>Produkt bezpieczny - brak alergenów.</p>
        @endif

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #dee2e6;">

        <h2>Opinie klientów</h2>

        @auth
            <form action="{{ route('reviews.store', $product) }}" method="POST"
                style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <h3 style="margin-top: 0;">Dodaj swoją opinię</h3>

                <div class="form-group">
                    <label for="rating">Ocena:</label>
                    {{-- NAPRAWA BŁĘDU SELECT: Dodano pustą opcję --}}
                    <select name="rating" id="rating" required>
                        <option value="" disabled selected>-- Wybierz ocenę --</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                        <option value="3">⭐⭐⭐ (3/5)</option>
                        <option value="2">⭐⭐ (2/5)</option>
                        <option value="1">⭐ (1/5)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Treść:</label>
                    <textarea name="content" id="content" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Wyślij opinię</button>
            </form>
        @endauth

        @foreach($product->reviews as $review)
            <div style="border-bottom: 1px solid #eee; padding: 15px 0; position: relative;">
                <strong>{{ $review->user->name }}</strong>
                <span style="color: var(--warning);">{{ str_repeat('★', $review->rating) }}</span>
                <p style="margin: 5px 0;">{{ $review->content }}</p>
                <small style="color: var(--text-muted);">{{ $review->created_at->format('d.m.Y H:i') }}</small>

                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                        <div style="position: absolute; top: 15px; right: 0;">
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                onsubmit="return confirm('Usunąć tę opinię?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('DELETE')
                                <button type="submit" class="btn btn-red" style="font-size: 0.7em; padding: 2px 6px;">Usuń</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>
@endsection