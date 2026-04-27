<?php

namespace Database\Factories;

use App\Models\Mesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    protected $model = Mesa::class;

    public function definition(): array
    {
        $estados = ['libre', 'ocupado', 'reservado'];
        $estado = $this->faker->randomElement($estados);
        
        return [
            'numero_mesa' => $this->faker->unique()->numberBetween(1, 50),
            'estado' => $estado,
            'area' => $this->faker->randomElement(['Planta Baja', 'Segundo Piso', 'Jardín PB', 'Terraza', 'VIP', 'Terraza Exterior']),
            'capacidad' => $this->faker->randomElement([2, 4, 6, 8, 10, 12]),
            'hora_reserva' => $estado === 'reservado' ? $this->faker->dateTimeBetween('now', '+7 days') : null,
            'cliente_reserva' => $estado === 'reservado' ? $this->faker->name() : null,
            'telefono_reserva' => $estado === 'reservado' ? $this->faker->phoneNumber() : null, // phoneNumber() devuelve string
        ];
    }
}