<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aluno>
 */
class AlunoFactory extends Factory
{
    protected $model = Aluno::class;

    public function definition(): array
    {
        return [
            'numero_processo' => 'P'.fake()->unique()->numerify('####'),
            'nome' => fake()->name(),
            'data_nascimento' => fake()->dateTimeBetween('-18 years', '-10 years')->format('Y-m-d'),
            'genero' => fake()->randomElement(['F', 'M']),
            'encarregado_nome' => fake()->name(),
            'encarregado_contacto' => '9'.fake()->numerify('########'),
            'turma_id' => Turma::factory(),
            'ativo' => true,
        ];
    }
}
