@extends('layout')

@section('content')
    <h1>Edytuj Produkt: {{ $product->name }}</h1>

    {{-- Wyświetlanie błędów walidacji (np. ujemna cena) --}}
    @if ($errors->any())
        <div style="background: #ffcccc; padding: 15px; margin-bottom: 20px; border: 1px solid red; border-radius: 5px;">
            <strong style="color: #cc0000;">Wystąpiły błędy:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nazwa:</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-control" style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label>Cena (PLN):</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label>Kcal / 100g:</label>
            <input type="number" name="kcal_per_100g" value="{{ old('kcal_per_100g', $product->kcal_per_100g) }}" required style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label>Marka:</label>
            <select name="brand_id" required style="width: 100%; padding: 8px;">
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" 
                        {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
                        {{ $brand->name }} ({{ $brand->country_of_origin }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kategoria:</label>
            <select name="category_id" required style="width: 100%; padding: 8px;">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Alergeny:</label>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: white;">
                @foreach($allergens as $allergen)
                    @php
                        $isChecked = $product->allergens->contains($allergen->id);
                        // Obsługa old() w przypadku błędu walidacji
                        if(old('allergens')) {
                            $isChecked = in_array($allergen->id, old('allergens'));
                        }
                    @endphp
                    <div style="margin-bottom: 5px;">
                        <label style="font-weight: normal; display: block; cursor: pointer;">
                            <input type="checkbox" name="allergens[]" value="{{ $allergen->id }}" 
                                {{ $isChecked ? 'checked' : '' }}>
                            {{ $allergen->name }} 
                            <small style="color: gray; font-weight: bold;">({{ $allergen->severity }})</small>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn" style="background: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;">Zaktualizuj Produkt</button>
            <a href="{{ route('products.index') }}" class="btn" style="margin-left: 10px;">Anuluj</a>
        </div>
    </form>
@endsection