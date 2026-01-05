@extends('layout')

@section('content')
    <h1>Zarządzanie Użytkownikami</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Email</th>
                <th>Obecna Rola</th>
                <th>Zmień Rolę</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{-- Kolorowanie roli dla czytelności --}}
                        @if($user->role == 'admin') <strong style="color:red">ADMIN</strong>
                        @elseif($user->role == 'employee') <strong style="color:blue">Pracownik</strong>
                        @else Klient
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                            style="display:flex; gap:5px;">
                            @csrf
                            @method('PATCH')
                            <select name="role" style="padding: 5px;">
                                <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Klient</option>
                                <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Pracownik</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit" class="btn" style="font-size:0.8em;">Zapisz</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Usunąć tego użytkownika?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red" style="font-size:0.8em;">Usuń</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $users->links() }}</div>
@endsection