@extends('layout')

@section('content')
    <h1>Zarządzanie Użytkownikami</h1>

    <div class="table-responsive">
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
                        <td data-label="ID">{{ $user->id }}</td>
                        <td data-label="Nazwa">{{ $user->name }}</td>
                        <td data-label="Email">{{ $user->email }}</td>
                        <td data-label="Obecna Rola">
                            @if($user->role == 'admin') <strong style="color:var(--danger)">ADMIN</strong>
                            @elseif($user->role == 'employee') <strong style="color:var(--primary)">Pracownik</strong>
                            @else Klient
                            @endif
                        </td>
                        <td data-label="Zmień Rolę">
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                                style="display:flex; gap:5px; align-items: center;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('PATCH')
                                <select name="role" style="padding: 5px;">
                                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Klient</option>
                                    <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Pracownik</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="btn" style="font-size:0.8em;">Zapisz</button>
                            </form>
                        </td>
                        <td data-label="Akcje">
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Usunąć tego użytkownika?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('DELETE')
                                <button type="submit" class="btn btn-red" style="font-size:0.8em;">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links() }}</div>
@endsection