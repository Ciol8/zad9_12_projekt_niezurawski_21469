@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Lista Produktów</h1>
        
        {{-- Przycisk Dodawania: Tylko dla Admina/Pracownika --}}
        @auth
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                <a href="{{ route('products.create') }}" class="btn" style="background: #28a745; color: white;">Dodaj nowy produkt</a>
            @endif
        @endauth
    </div>

    <table>
        <thead>
    <tr>
        <th>
            Nazwa Produktu 
            <a href="{{ route('products.index', ['sort' => 'name', 'direction' => 'asc']) }}">⬆</a>
            <a href="{{ route('products.index', ['sort' => 'name', 'direction' => 'desc']) }}">⬇</a>
        </th>
        <th>
            Cena
            <a href="{{ route('products.index', ['sort' => 'price', 'direction' => 'asc']) }}">⬆</a>
            <a href="{{ route('products.index', ['sort' => 'price', 'direction' => 'desc']) }}">⬇</a>
        </th>
        <th>Kcal / 100g</th>
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
                {{-- Dodajemy link do szczegółów produktu po kliknięciu w wiersz (opcjonalne) --}}
                <tr style="cursor: pointer;" onclick="window.location='{{ route('products.show', $product) }}'">
                    <td>
                        {{-- Link do podglądu --}}
                        <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit; font-weight: bold;">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td>{{ number_format($product->price, 2) }} PLN</td>
                    <td>{{ $product->kcal_per_100g }}</td>
                    <td>{{ $product->brand->name }} <small
                            style="color: gray;">({{ $product->brand->country_of_origin }})</small></td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        @foreach($product->allergens as $allergen)
                            @php
                                // Logika kolorów
                                $color = '#6c757d'; 
                                $textColor = 'white';

                                switch ($allergen->severity) {
                                    case 'low': $color = '#28a745'; break;
                                    case 'medium': 
                                        $color = '#ffc107'; 
                                        $textColor = 'black'; 
                                        break;
                                    case 'high': $color = '#dc3545'; break;
                                    case 'deadly': $color = '#800080'; break; // Poprawiłem hex na standardowy fiolet
                                }
                            @endphp
                            <span
                                style="background-color: {{ $color }}; color: {{ $textColor }}; padding: 3px 8px; border-radius: 12px; font-size: 0.85em; display: inline-block; margin: 1px;">
                                {{ $allergen->name }}
                            </span>
                        @endforeach
                    </td>
                    
                    {{-- Przyciski Akcji: Tylko dla Admina/Pracownika --}}
                    @auth
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                            {{-- onclick stopPropagation zapobiega przejściu do szczegółów przy kliknięciu w przycisk --}}
                            <td onclick="event.stopPropagation();">
                                <a href="{{ route('products.edit', $product) }}" class="btn">Edytuj</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-red"
                                        onclick="return confirm('Czy na pewno usunąć?')">Usuń</button>
                                </form>
                            </td>
                        @endif
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $products->links() }}
    </div>
@endsection