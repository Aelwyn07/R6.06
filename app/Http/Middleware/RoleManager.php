<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (!$userRole) {
            return redirect()->route('logout');
        }

        $roleLevels = [
            'admin' => [0],
            'extended_reader' => [1, 2],
            'reader' => [1, 2]
        ];

        if (isset($roleLevels[$role]) && in_array($userRole->level, $roleLevels[$role], true)) {
            return $next($request);
        }

        return match ($userRole->level) {
            0 => redirect()->route('provisionnal_calendar.groups'),
            1, 2 => redirect()->route('provisionnal_calendar'),
            default => redirect()->route('logout'),
        };
    }
}