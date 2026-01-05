@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Edytuj Produkt: {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn">Anuluj</a>
    </div>

    {{-- Wyświetlanie błędów walidacji --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Wystąpiły błędy:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        {{-- Ręczny token CSRF dla walidacji W3C --}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @method('PUT')

        <div class="form-group">
            <label for="name">Nazwa:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="price">Cena (PLN):</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label for="kcal_per_100g">Kcal / 100g:</label>
            <input type="number" id="kcal_per_100g" name="kcal_per_100g" value="{{ old('kcal_per_100g', $product->kcal_per_100g) }}" required>
        </div>

        <div class="form-group">
            <label for="brand_id">Marka:</label>
            <select name="brand_id" id="brand_id" required>
                {{-- NAPRAWA BŁĘDU: Dodano pustą opcję startową --}}
                <option value="" disabled>-- Wybierz markę --</option>
                
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" 
                        {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
                        {{ $brand->name }} ({{ $brand->country_of_origin }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category_id">Kategoria:</label>
            <select name="category_id" id="category_id" required>
                {{-- NAPRAWA BŁĘDU: Dodano pustą opcję startową --}}
                <option value="" disabled>-- Wybierz kategorię --</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <h2>Alergeny:</h2>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ced4da; padding: 10px; background: white; border-radius: 4px;">
                @foreach($allergens as $allergen)
                    @php
                        // Sprawdzamy czy produkt ma ten alergen (dla edycji)
                        $isChecked = $product->allergens->contains($allergen->id);
                        // Obsługa old() w przypadku błędu walidacji
                        if(old('allergens')) {
                            $isChecked = in_array($allergen->id, old('allergens'));
                        }
                    @endphp
                    <div style="margin-bottom: 5px;">
                        <input type="checkbox" id="allergen-{{ $allergen->id }}" name="allergens[]" value="{{ $allergen->id }}" {{ $isChecked ? 'checked' : '' }}>
                        <label for="allergen-{{ $allergen->id }}" style="display: inline; font-weight: normal; margin-left: 5px; cursor: pointer;">
                            {{ $allergen->name }} 
                            <small style="color: var(--text-muted);">({{ $allergen->severity }})</small>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Zaktualizuj Produkt</button>
        </div>
    </form>
@endsection