@extends('layout')

@section('content')
    <h1>Panel Użytkownika</h1>
    <div style="padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <p>Witaj, <strong>{{ Auth::user()->name }}</strong>!</p>
        <p>Jesteś zalogowany w sklepie.</p>
        
        <hr>
        
        <h3>Szybkie akcje:</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 10px;">
                <a href="{{ route('products.index') }}" class="btn">🛒 Przejdź do zakupów</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="{{ route('cart.index') }}" class="btn">📦 Zobacz koszyk</a>
            </li>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                <li style="margin-bottom: 10px;">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">📋 Panel Zamówień</a>
                </li>
            @endif
        </ul>
    </div>
@endsection