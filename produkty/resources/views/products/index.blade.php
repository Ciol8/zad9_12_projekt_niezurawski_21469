@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Lista Produktów</h1>
        <a href="{{ route('products.create') }}" class="btn" style="background: #28a745; color: white;">Dodaj nowy
            produkt</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nazwa Produktu</th>
                <th>Cena</th>
                <th>Kcal / 100g</th>
                <th>Marka</th>
                <th>Kategoria</th>
                <th>Alergeny</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }} PLN</td>
                    <td>{{ $product->kcal_per_100g }}</td>
                    <td>{{ $product->brand->name }} <small
                            style="color: gray;">({{ $product->brand->country_of_origin }})</small></td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        @foreach($product->allergens as $allergen)
                            @php
                                // Logika kolorów
                                $color = '#6c757d'; // domyślny szary
                                $textColor = 'white';

                                switch ($allergen->severity) {
                                    case 'low':
                                        $color = '#28a745'; // Zielony
                                        break;
                                    case 'medium':
                                        $color = '#ffc107'; // Żółty/Pomarańczowy
                                        $textColor = 'black';
                                        break;
                                    case 'high':
                                        $color = '#dc3545'; // Czerwony
                                        break;
                                    case 'deadly':
                                        $color = '#080008ff'; // Fioletowy
                                        break;
                                }
                            @endphp
                            <span
                                style="background-color: {{ $color }}; color: {{ $textColor }}; padding: 3px 8px; border-radius: 12px; font-size: 0.85em; display: inline-block; margin: 1px;">
                                {{ $allergen->name }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn">Edytuj</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red"
                                onclick="return confirm('Czy na pewno usunąć?')">Usuń</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $products->links() }}
    </div>
@endsection