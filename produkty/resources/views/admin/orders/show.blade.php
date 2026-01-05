@extends('layout')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Zamówienie #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn">« Powrót do listy</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">

        {{-- Lewa kolumna: Produkty --}}
        <div style="background: white; padding: 20px; border: 1px solid #ddd;">
            <h3>Produkty</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Cena (w dniu zakupu)</th>
                        <th>Ilość</th>
                        <th>Suma</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="font-weight: bold; background: #f9f9f9;">
                        <td colspan="3" style="text-align: right;">RAZEM:</td>
                        <td>{{ number_format($order->total_price, 2) }} PLN</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Prawa kolumna: Dane i Status --}}
        <div>
            <div style="background: white; padding: 20px; border: 1px solid #ddd; margin-bottom: 20px;">
                <h3>Dane Klienta</h3>
                <p><strong>Imię:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Adres dostawy:</strong><br> {{ $order->shipping_address }}</p>
                <p><strong>Data zamówienia:</strong> {{ $order->created_at }}</p>
            </div>

            <div style="background: #e9f7ef; padding: 20px; border: 1px solid #28a745;">
                <h3 style="color: #155724;">Zarządzanie Statusem</h3>

                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label>Zmień status na:</label>
                        <select name="order_status_id" class="form-control" style="padding: 10px; width: 100%;">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn"
                        style="width: 100%; background: #28a745; color: white; margin-top: 10px;">
                        Zaktualizuj Status
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection