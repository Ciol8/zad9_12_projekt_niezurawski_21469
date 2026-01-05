@extends('layout')

@section('content')
    <h1>Wszystkie Zamówienia</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Klient</th>
                <th>Kwota</th>
                <th>Status</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $order->user->name }} ({{ $order->user->email }})</td>
                    <td>{{ number_format($order->total_price, 2) }} PLN</td>
                    <td>
                        <span style="padding: 5px 10px; border-radius: 5px; background: #eee; font-weight: bold;">
                            {{ $order->status->name }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn"
                            style="background: #007bff; color: white;">Szczegóły / Zmień status</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $orders->links() }}</div>
@endsection