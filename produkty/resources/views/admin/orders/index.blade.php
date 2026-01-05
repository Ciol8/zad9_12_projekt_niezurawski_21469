@extends('layout')

@section('content')
    <h1>Wszystkie Zamówienia</h1>

    <div class="table-responsive">
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
                        <td data-label="ID">#{{ $order->id }}</td>
                        <td data-label="Data">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td data-label="Klient">
                            {{ $order->user->name }}<br>
                            <small>{{ $order->user->email }}</small>
                        </td>
                        <td data-label="Kwota">{{ number_format($order->total_price, 2) }} PLN</td>
                        <td data-label="Status">
                            <span
                                style="padding: 5px 10px; border-radius: 5px; background: #e9ecef; font-weight: bold; font-size: 0.9em;">
                                {{ $order->status->name }}
                            </span>
                        </td>
                        <td data-label="Akcja">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary"
                                style="font-size: 0.9em;">Szczegóły</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
@endsection