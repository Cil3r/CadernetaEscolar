<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\Falta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Falta>
 */
class FaltaFactory extends Factory
{
    protected $model = Falta::class;

    public function definition(): array
    {
        $justificada = fake()->boolean();

        return [
            'aluno_id' => Aluno::factory(),
            'user_id' => User::factory(),
            'data' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'justificada' => $justificada,
            'motivo' => $justificada ? fake()->randomElement(['Doença', 'Consulta médica', 'Assunto familiar']) : null,
        ];
    }
}
