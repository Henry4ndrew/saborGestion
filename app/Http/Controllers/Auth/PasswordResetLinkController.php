<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
            'email.exists' => '❌ Este correo electrónico no está registrado en nuestro sistema.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', '✅ ¡Enlace enviado! Revisa tu correo electrónico.');
        }

        return back()->withErrors([
            'email' => [__($status)],
        ]);
    }
}