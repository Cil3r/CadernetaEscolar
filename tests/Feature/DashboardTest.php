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

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_convidado_e_redirecionado_para_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_utilizador_autenticado_ve_o_dashboard(): void
    {
        $user = User::factory()->direcao()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_direcao_ve_totais_de_todas_as_turmas(): void
    {
        Configuracao::insert([
            ['chave' => 'limite_faltas', 'valor' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['chave' => 'limite_gravidade', 'valor' => '4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $direcao = User::factory()->direcao()->create();
        $turma = Turma::factory()->create();
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);
        Ocorrencia::factory()->create(['aluno_id' => $aluno->id, 'gravidade' => 5, 'data' => now()]);
        Falta::factory()->create(['aluno_id' => $aluno->id, 'justificada' => false, 'data' => now()]);

        $response = $this->actingAs($direcao)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Alunos em alerta');
        $response->assertSee($aluno->nome);
    }

    public function test_professor_so_ve_dados_das_suas_turmas(): void
    {
        $professor = User::factory()->professor()->create();
        $turmaDoProfessor = Turma::factory()->create();
        $turmaDoProfessor->professores()->attach($professor->id);
        $outraTurma = Turma::factory()->create();

        Aluno::factory()->create(['turma_id' => $turmaDoProfessor->id]);
        Aluno::factory()->create(['turma_id' => $outraTurma->id]);

        $response = $this->actingAs($professor)->get('/dashboard');

        $response->assertStatus(200);
    }
}
