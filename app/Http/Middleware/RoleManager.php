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

        switch ($role) {
            case 'admin':
                if ($userRole->level === 0) {
                    return $next($request);
                }
                break;
            case 'extended_reader':
            case 'reader':
                if ($userRole->level === 1 || $userRole->level === 2) {
                    return $next($request);
                }
                break;
        }

        switch ($userRole->level) {
            case 0:
                return redirect()->route('provisionnal_calendar.groups');
            case 1:
            case 2:
                return redirect()->route('provisionnal_calendar');
        }

        return redirect()->route('logout');
    }
}
