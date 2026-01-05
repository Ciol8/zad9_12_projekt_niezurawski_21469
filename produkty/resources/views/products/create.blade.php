@extends('layout')

@section('content')
    <h1>Dodaj Nowy Produkt</h1>

    {{-- Wyświetlanie błędów walidacji --}}
    @if ($errors->any())
        <div style="background: #ffcccc; padding: 10px; margin-bottom: 20px; border: 1px solid red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
            <label>Nazwa Produktu:</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
        </div>

        <div class="form-group">
            <label>Cena (PLN):</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label>Kcal / 100g:</label>
            <input type="number" name="kcal_per_100g" value="{{ old('kcal_per_100g') }}" required>
        </div>

        <div class="form-group">
            <label>Marka:</label>
            <select name="brand_id" required>
                <option value="">-- Wybierz markę --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }} ({{ $brand->country_of_origin }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kategoria:</label>
            <select name="category_id" required>
                <option value="">-- Wybierz kategorię --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Alergeny (zaznacz pasujące):</label>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #fff;">
                @foreach($allergens as $allergen)
                    <div style="margin-bottom: 5px;">
                        <label style="font-weight: normal; display: inline;">
                            <input type="checkbox" name="allergens[]" value="{{ $allergen->id }}" {{ (is_array(old('allergens')) && in_array($allergen->id, old('allergens'))) ? 'checked' : '' }}>
                            {{ $allergen->name }}
                            <small style="color: gray;">({{ $allergen->severity }})</small>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn"
                style="background: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;">Zapisz
                Produkt</button>
            <a href="{{ route('products.index') }}" class="btn" style="margin-left: 10px;">Anuluj</a>
        </div>
    </form>
@endsection