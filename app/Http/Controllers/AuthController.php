<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\DB; // Agregar esta línea
use Carbon\Carbon; // Agregar esta línea

class AuthController extends Controller
{
    // Registro de usuario (igual)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
            'celular' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente',
            'celular' => $request->celular,
            'direccion' => $request->direccion,
            'score' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Login de usuario (igual)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Logout (igual)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout exitoso'
        ]);
    }

    // Obtener usuario actual (igual)
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // Enviar link de recuperación de contraseña (MODIFICADO - VERSIÓN API)
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Eliminar tokens anteriores para este email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        // Generar token único
        $token = Str::random(64);
        
        // Guardar token en la base de datos
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);
        
        // Devolver token al frontend (Postman)
        return response()->json([
            'success' => true,
            'message' => 'Token generado exitosamente',
            'data' => [
                'email' => $request->email,
                'token' => $token,
                'expires_in' => 60 // minutos
            ]
        ], 200);
    }

    // Resetear contraseña (MODIFICADO - VERSIÓN API)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);
        
        // Buscar el token en la base de datos
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
        
        // Verificar si existe el token
        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró solicitud de reset para este email'
            ], 400);
        }
        
        // Verificar que el token sea válido
        if (!Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido'
            ], 400);
        }
        
        // Verificar expiración (60 minutos)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->diffInMinutes(Carbon::now()) > 60) {
            // Eliminar token expirado
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            
            return response()->json([
                'success' => false,
                'message' => 'Token expirado. Solicita un nuevo reset'
            ], 400);
        }
        
        // Actualizar la contraseña del usuario
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente'
        ], 200);
    }
}