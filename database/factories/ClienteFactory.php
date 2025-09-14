<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
        protected $model = Cliente::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rut_empresa' => $this->faker->unique()->numerify('########-#'),
            'rubro' => $this->faker->word(),
            'razon_social' => $this->faker->company(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'nombre_contacto' => $this->faker->name(),
            'email_contacto' => $this->faker->unique()->safeEmail()
        ];
    }
}
