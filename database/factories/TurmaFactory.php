<?php

namespace Database\Factories;

use App\Models\Turma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Turma>
 */
class TurmaFactory extends Factory
{
    protected $model = Turma::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->numberBetween(7, 12).'.ª '.fake()->randomElement(['A', 'B', 'C']),
            'classe' => fake()->numberBetween(7, 12).'.ª Classe',
            'curso' => fake()->randomElement(['Informática', 'Ciências Físicas e Biológicas', 'Económico-Jurídico']),
            'ano_letivo' => '2026/2027',
            'periodo' => fake()->randomElement(['manha', 'tarde', 'noite']),
        ];
    }
}
