<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        // Admin ma dostęp do wszystkiego
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Jeśli wymagana rola to 'employee', a użytkownik to 'employee' -> OK
        if ($role === 'employee' && $userRole === 'employee') {
            return $next($request);
        }

        // Jeśli wymagana rola to 'client' -> OK
        if ($role === 'client' && $userRole === 'client') {
            return $next($request);
        }

        // W przeciwnym razie brak dostępu (403 Forbidden)
        abort(403, 'Brak uprawnień do tej strony.');
    }
}