<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = $this->faker->firstName();
        $apellido = $this->faker->lastName();
        $rut = $this->faker->unique()->numerify('########-#'); 

        return [
            'rut' => $rut,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => strtolower($nombre . '.' . $apellido . '@ventasfix.cl'),
            'password' => bcrypt('password'), 
        ];
    }
}
