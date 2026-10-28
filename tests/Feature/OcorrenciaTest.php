<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Ocorrencia;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OcorrenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_professor_pode_registar_ocorrencia_para_aluno_da_sua_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach($professor->id);
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);

        $response = $this->actingAs($professor)->post('/ocorrencias', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'tipo' => 'comportamento',
            'descricao' => 'Chegou atrasado à aula.',
            'gravidade' => 3,
        ]);

        $response->assertRedirect(route('ocorrencias.index'));
        $this->assertDatabaseHas('ocorrencias', [
            'aluno_id' => $aluno->id,
            'user_id' => $professor->id,
            'gravidade' => 3,
        ]);
    }

    public function test_professor_nao_pode_registar_ocorrencia_para_aluno_de_outra_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($professor)->post('/ocorrencias', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'tipo' => 'comportamento',
            'descricao' => 'Teste.',
            'gravidade' => 2,
        ]);

        $response->assertSessionHasErrors('aluno_id');
        $this->assertDatabaseMissing('ocorrencias', ['aluno_id' => $aluno->id]);
    }

    public function test_validacao_rejeita_gravidade_fora_do_intervalo(): void
    {
        $direcao = User::factory()->direcao()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($direcao)->post('/ocorrencias', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'tipo' => 'comportamento',
            'descricao' => 'Teste.',
            'gravidade' => 9,
        ]);

        $response->assertSessionHasErrors('gravidade');
    }

    public function test_professor_nao_pode_editar_ocorrencia_de_outro_professor(): void
    {
        $professor = User::factory()->professor()->create();
        $outroProfessor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach([$professor->id, $outroProfessor->id]);
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);
        $ocorrencia = Ocorrencia::factory()->create(['aluno_id' => $aluno->id, 'user_id' => $outroProfessor->id]);

        $response = $this->actingAs($professor)->put("/ocorrencias/{$ocorrencia->id}", [
            'data' => now()->format('Y-m-d'),
            'tipo' => 'comportamento',
            'descricao' => 'Alterado.',
            'gravidade' => 1,
        ]);

        $response->assertForbidden();
    }

    public function test_direcao_pode_ver_e_filtrar_ocorrencias(): void
    {
        $direcao = User::factory()->direcao()->create();
        $aluno = Aluno::factory()->create();
        Ocorrencia::factory()->create(['aluno_id' => $aluno->id, 'descricao' => 'Ocorrência visível', 'gravidade' => 5]);

        $response = $this->actingAs($direcao)->get('/ocorrencias?gravidade=5');

        $response->assertStatus(200);
        $response->assertSee($aluno->nome);
    }
}
