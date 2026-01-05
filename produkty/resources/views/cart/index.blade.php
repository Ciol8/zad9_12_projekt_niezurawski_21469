@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Twój Koszyk</h1>
        <a href="{{ route('products.index') }}" class="btn">« Wróć do sklepu</a>
    </div>

    @if(count($products) > 0)
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
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }} PLN</td>
                    <td>
                        {{-- Formularz zmiany ilości --}}
                        <form action="{{ route('cart.update', $product->id) }}" method="POST" style="display:flex; gap:5px;">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $product->quantity }}" min="1" style="width: 50px;">
                            <button type="submit" class="btn" style="font-size: 0.8em;">↻</button>
                        </form>
                    </td>
                    <td>{{ number_format($product->subtotal, 2) }} PLN</td>
                    <td>
                        <a href="{{ route('cart.remove', $product->id) }}" class="btn btn-red">Usuń</a>
                    </td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="3" style="text-align: right;">ŁĄCZNIE:</td>
                    <td colspan="2" style="font-size: 1.2em; color: #28a745;">{{ number_format($totalSum, 2) }} PLN</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; padding: 20px; border: 1px solid #ddd;">
            <h3>Dane do wysyłki</h3>
            @auth
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Ulica i numer:</label>
                            <input type="text" name="street" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Kod pocztowy:</label>
                            <input type="text" name="zip" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Miejscowość:</label>
                            <input type="text" name="city" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Telefon:</label>
                            <input type="text" name="phone" required class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn" style="background: #28a745; color: white; margin-top: 20px; width: 100%;">
                        Złóż zamówienie
                    </button>
                </form>
            @else
                <p>Zaloguj się, aby zamówić.</p>
            @endauth
        </div>
    @else
        <p>Koszyk jest pusty.</p>
        <a href="{{ route('products.index') }}" class="btn">Idź na zakupy</a>
    @endif
@endsection