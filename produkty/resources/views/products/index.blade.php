@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h1>Lista Produktów</h1>
        
        @auth
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                <a href="{{ route('products.create') }}" class="btn btn-primary">Dodaj nowy produkt</a>
            @endif
        @endauth
    </div>

    {{-- PASEK FILTRÓW --}}
    <div style="background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 20px;">
        <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end;">
            
            {{-- Zachowujemy sortowanie przy filtrowaniu --}}
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">

            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                <label for="brand_id">Filtruj wg Marki:</label>
                <select name="brand_id" id="brand_id" onchange="this.form.submit()">
                    <option value="">-- Wszystkie Marki --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $selectedBrand == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                <label for="category_id">Filtruj wg Kategorii:</label>
                <select name="category_id" id="category_id" onchange="this.form.submit()">
                    <option value="">-- Wszystkie Kategorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="padding-bottom: 2px;">
                <a href="{{ route('products.index') }}" class="btn" style="font-size: 0.9em;">Wyczyść filtry</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>
                        Nazwa Produktu 
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'asc'])) }}" style="text-decoration: none;">⬆</a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'desc'])) }}" style="text-decoration: none;">⬇</a>
                    </th>
                    <th>
                        Cena
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'direction' => 'asc'])) }}" style="text-decoration: none;">⬆</a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'direction' => 'desc'])) }}" style="text-decoration: none;">⬇</a>
                    </th>
                    <th>
                        Kcal / 100g
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'kcal_per_100g', 'direction' => 'asc'])) }}" style="text-decoration: none;">⬆</a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'kcal_per_100g', 'direction' => 'desc'])) }}" style="text-decoration: none;">⬇</a>
                    </th>
                    <th>Marka</th>
                    <th>Kategoria</th>
                    <th>Alergeny</th>
                    @auth
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                            <th>Akcje</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr style="cursor: pointer;" onclick="window.location='{{ route('products.show', $product) }}'">
                        <td data-label="Nazwa">
                            <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit; font-weight: bold;">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td data-label="Cena">{{ number_format($product->price, 2) }} PLN</td>
                        <td data-label="Kcal">{{ $product->kcal_per_100g }}</td>
                        <td data-label="Marka">
                            {{ $product->brand->name }} 
                            <small style="color: gray;">({{ $product->brand->country_of_origin }})</small>
                        </td>
                        <td data-label="Kategoria">{{ $product->category->name }}</td>
                        <td data-label="Alergeny">
                            @foreach($product->allergens as $allergen)
                                @php
                                    $color = '#6c757d'; 
                                    $textColor = 'white';
                                    switch ($allergen->severity) {
                                        case 'low': $color = '#28a745'; break;
                                        case 'medium': $color = '#ffc107'; $textColor = 'black'; break;
                                        case 'high': $color = '#dc3545'; break;
                                        case 'deadly': $color = '#800080'; break;
                                    }
                                @endphp
                                <span style="background-color: {{ $color }}; color: {{ $textColor }}; padding: 3px 8px; border-radius: 12px; font-size: 0.85em; display: inline-block; margin: 1px;">
                                    {{ $allergen->name }}
                                </span>
                            @endforeach
                        </td>
                        
                        @auth
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                                <td data-label="Akcje" onclick="event.stopPropagation();">
                                    <a href="{{ route('products.edit', $product) }}" class="btn" style="font-size: 0.8em;">Edytuj</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red" style="font-size: 0.8em;" onclick="return confirm('Czy na pewno usunąć?')">Usuń</button>
                                    </form>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
@endsection