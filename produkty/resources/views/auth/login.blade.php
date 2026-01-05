@extends('layout')

@section('content')
    <div style="max-width: 400px; margin: 0 auto;">
        <h1 style="text-align: center;">Logowanie</h1>

        <form method="POST" action="{{ route('login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Hasło</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password') <span style="color: red; font-size: 0.9em;">{{ $message }}</span> @enderror
            </div>

            

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                

                <button type="submit" class="btn btn-primary">
                    Zaloguj się
                </button>
            </div>
            
            <div style="margin-top: 20px; text-align: center; border-top: 1px solid #ddd; padding-top: 20px;">
                Nie masz konta? <a href="{{ route('register') }}">Zarejestruj się</a>
            </div>
        </form>
    </div>
@endsection