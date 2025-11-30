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

        // User belum login
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Convert string route parameters → Enum
        $allowedRoles = array_map(fn($r) => UserRole::from($r), $roles);

        // Check apakah user.role ada dalam allowed role
        if (!in_array($user->role, $allowedRoles)) {
            return abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
