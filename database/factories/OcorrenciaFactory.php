<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ocorrencia>
 */
class OcorrenciaFactory extends Factory
{
    protected $model = Ocorrencia::class;

    public function definition(): array
    {
        return [
            'aluno_id' => Aluno::factory(),
            'user_id' => User::factory(),
            'data' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'tipo' => fake()->randomElement(['comportamento', 'academico', 'disciplinar', 'elogio']),
            'descricao' => fake()->sentence(10),
            'gravidade' => fake()->numberBetween(1, 5),
        ];
    }
}
