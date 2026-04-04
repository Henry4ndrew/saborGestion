<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class CustomPasswordResetLinkController extends Controller
{
    /**
     * Muestra el formulario para solicitar el enlace de recuperación
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de recuperación de contraseña
     */
    public function store(Request $request)
    {
        // Validación con mensajes personalizados
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
            'email.exists' => '❌ Este correo electrónico no está registrado en nuestro sistema.',
        ]);

        // Intentar enviar el enlace
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Verificar el resultado
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => '✅ ¡Enlace enviado! Revisa tu correo electrónico para restablecer tu contraseña.',
            ]);
        }

        // Si hay error
        return back()->withErrors([
            'email' => '❌ No pudimos enviar el enlace. Por favor intenta de nuevo.',
        ]);
    }
}