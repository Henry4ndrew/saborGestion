<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'celular',
        'direccion',
        'score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Métodos de ayuda para roles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMesero()
    {
        return $this->role === 'mesero';
    }

    public function isCocinero()
    {
        return $this->role === 'cocinero';
    }

    public function isCajero()
    {
        return $this->role === 'cajero';
    }

    public function isCliente()
    {
        return $this->role === 'cliente';
    }
}