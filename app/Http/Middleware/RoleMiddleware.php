<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->withError('Silakan login terlebih dahulu');
        }
        $allowedRoles = array_map(fn($r) => UserRole::from($r), $roles);
        if (!in_array($user->role, $allowedRoles)) {
            return abort(403, 'You do not have permission to access this resource.');
        }
        return $next($request);
    }
}
