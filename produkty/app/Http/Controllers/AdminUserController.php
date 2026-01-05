<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        // ZMIANA: Sortowanie po ID malejąco
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('id', 'desc') // <--- Tutaj zmiana
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Zmiana roli (Awans/Degradacja)
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,employee,client',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Zmieniono rolę użytkownika {$user->name} na {$request->role}.");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Użytkownik został usunięty.');
    }
}