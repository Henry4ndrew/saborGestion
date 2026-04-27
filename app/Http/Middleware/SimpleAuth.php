<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class SimpleAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado'
            ], 401);
        }
        
        // Buscar el usuario por el token (versión simple)
        $user = User::whereHas('tokens', function($query) use ($token) {
            $query->where('token', hash('sha256', $token));
        })->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido'
            ], 401);
        }
        
        auth()->login($user);
        
        return $next($request);
    }
}