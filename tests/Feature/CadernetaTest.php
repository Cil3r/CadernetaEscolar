<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Falta;
use App\Models\Ocorrencia;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CadernetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_direcao_ve_a_caderneta_do_aluno_com_historico(): void
    {
        $direcao = User::factory()->direcao()->create();
        $aluno = Aluno::factory()->create();
        Ocorrencia::factory()->create(['aluno_id' => $aluno->id, 'descricao' => 'Chegou atrasado']);
        Falta::factory()->create(['aluno_id' => $aluno->id, 'justificada' => false]);

        $response = $this->actingAs($direcao)->get("/alunos/{$aluno->id}/caderneta");

        $response->assertStatus(200);
        $response->assertSee('Chegou atrasado');
    }

    public function test_professor_nao_acede_a_caderneta_de_aluno_de_outra_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($professor)->get("/alunos/{$aluno->id}/caderneta");

        $response->assertForbidden();
    }

    public function test_alerta_de_faltas_aparece_quando_limite_e_atingido(): void
    {
        Configuracao::insert([
            ['chave' => 'limite_faltas', 'valor' => '2', 'created_at' => now(), 'updated_at' => now()],
            ['chave' => 'limite_gravidade', 'valor' => '4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $direcao = User::factory()->direcao()->create();
        $turma = Turma::factory()->create();
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);
        Falta::factory()->count(3)->create(['aluno_id' => $aluno->id, 'justificada' => false, 'motivo' => null]);

        $response = $this->actingAs($direcao)->get("/alunos/{$aluno->id}/caderneta");

        $response->assertSee('limite de 2 faltas injustificadas');
    }
}
