@extends('layout')

@section('content')
    <div style="max-width: 400px; margin: 0 auto;">
        <h1 style="text-align: center;">Rejestracja</h1>

        <form method="POST" action="{{ route('register') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="name">Imię i Nazwisko</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Hasło</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Potwierdź Hasło</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 20px; gap: 15px;">
                <a href="{{ route('login') }}" style="font-size: 0.9em;">Masz już konto?</a>
                <button type="submit" class="btn btn-primary">
                    Zarejestruj się
                </button>
            </div>
        </form>
    </div>
@endsection