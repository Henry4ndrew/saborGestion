<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function administrador()
    {
        $this->authorizeRole('admin');
        return view('dashboard.administrador.index');
    }
    
    public function mesero()
    {
        $this->authorizeRole('mesero');
        return view('dashboard.mesero.index');
    }
    
    public function cocinero()
    {
        $this->authorizeRole('cocinero');
        return view('dashboard.cocinero.index');
    }
    
    public function cajero()
    {
        $this->authorizeRole('cajero');
        return view('dashboard.cajero.index');
    }
    
    public function cliente()
    {
        $this->authorizeRole('cliente');
        return view('dashboard.cliente.index');
    }

    private function authorizeRole($role)
    {
        if (Auth::user()->role !== $role && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    }
}