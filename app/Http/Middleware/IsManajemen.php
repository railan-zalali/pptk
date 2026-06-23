<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsManajemen
{
    /**
     * Handle an incoming request.
     * Only allows users with role 'manajemen'.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan 'manajemen' dan 'admin_ppkt' (superuser) untuk akses halaman monitoring
        if (!Auth::check() || !in_array(Auth::user()->role, ['manajemen', 'admin_ppkt'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
