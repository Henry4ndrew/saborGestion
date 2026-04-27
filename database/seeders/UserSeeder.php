<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@saborgestion.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mesero',
            'email' => 'mesero@saborgestion.com',
            'password' => Hash::make('password'),
            'role' => 'mesero',
        ]);

        User::create([
            'name' => 'Cocinero',
            'email' => 'cocinero@saborgestion.com',
            'password' => Hash::make('password'),
            'role' => 'cocinero',
        ]);

        User::create([
            'name' => 'Cajero',
            'email' => 'cajero@saborgestion.com',
            'password' => Hash::make('password'),
            'role' => 'cajero',
        ]);
    }
}