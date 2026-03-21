<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Redirigir según el rol
                switch ($user->role) {
                    case 'admin':
                        return redirect()->route('dashboard.administrador');
                    case 'mesero':
                        return redirect()->route('dashboard.mesero');
                    case 'cocinero':
                        return redirect()->route('dashboard.cocinero');
                    case 'cajero':
                        return redirect()->route('dashboard.cajero');
                    default:
                        return redirect()->route('dashboard.administrador');
                }
            }
        }

        return $next($request);
    }
}