@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Twój Koszyk</h1>
        <a href="{{ route('products.index') }}" class="btn">« Wróć do sklepu</a>
    </div>

    @if(count($products) > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Cena jedn.</th>
                        <th>Ilość</th>
                        <th>Suma</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td data-label="Produkt">{{ $product->name }}</td>
                        <td data-label="Cena jedn.">{{ number_format($product->price, 2) }} PLN</td>
                        <td data-label="Ilość">
                            <form action="{{ route('cart.update', $product->id) }}" method="POST" style="display:flex; gap:5px; align-items: center;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('PATCH')
                                <label for="quantity-{{$product->id}}" class="visually-hidden">Ilość</label>
                                <input type="number" id="quantity-{{$product->id}}" name="quantity" value="{{ $product->quantity }}" min="1" style="width: 60px;">
                                <button type="submit" class="btn" style="font-size: 0.8em; padding: 4px 8px;">↻</button>
                            </form>
                        </td>
                        <td data-label="Suma">{{ number_format($product->subtotal, 2) }} PLN</td>
                        <td data-label="Akcje">
                            <a href="{{ route('cart.remove', $product->id) }}" class="btn btn-red" style="font-size: 0.8em;">Usuń</a>
                        </td>
                    </tr>
                    @endforeach
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td colspan="3" data-label="Podsumowanie" style="text-align: right;">ŁĄCZNIE:</td>
                        <td colspan="2" data-label="Do zapłaty" style="font-size: 1.2em; color: #198754;">{{ number_format($totalSum, 2) }} PLN</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px;">
            {{-- H2: Naprawa hierarchii --}}
            <h2>Dane do wysyłki</h2>
            @auth
                <form action="{{ route('cart.checkout') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="street">Ulica i numer:</label>
                            <input type="text" id="street" name="street" required>
                        </div>
                        <div class="form-group">
                            <label for="zip">Kod pocztowy:</label>
                            <input type="text" id="zip" name="zip" required>
                        </div>
                        <div class="form-group">
                            <label for="city">Miejscowość:</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon:</label>
                            <input type="text" id="phone" name="phone" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">
                        Złóż zamówienie (Zobowiązanie do zapłaty)
                    </button>
                </form>
            @else
                <div class="alert alert-warning">
                    <p>Musisz być zalogowany, aby złożyć zamówienie.</p>
                    <a href="{{ route('login') }}" class="btn">Zaloguj się</a>
                </div>
            @endauth
        </div>
    @else
        <div style="text-align: center; padding: 40px;">
            <h2>Koszyk jest pusty.</h2>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Idź na zakupy</a>
        </div>
    @endif
@endsection