<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoTest extends TestCase
{
    use RefreshDatabase;

    public function test_direcao_pode_criar_aluno_em_qualquer_turma(): void
    {
        $direcao = User::factory()->direcao()->create();
        $turma = Turma::factory()->create();

        $response = $this->actingAs($direcao)->post('/alunos', [
            'numero_processo' => 'P0099',
            'nome' => 'Aluno Teste',
            'turma_id' => $turma->id,
            'ativo' => '1',
        ]);

        $this->assertDatabaseHas('alunos', ['numero_processo' => 'P0099', 'turma_id' => $turma->id]);
        $response->assertRedirect();
    }

    public function test_professor_pode_criar_aluno_na_sua_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach($professor->id);

        $response = $this->actingAs($professor)->post('/alunos', [
            'numero_processo' => 'P0100',
            'nome' => 'Aluno da Turma',
            'turma_id' => $turma->id,
            'ativo' => '1',
        ]);

        $this->assertDatabaseHas('alunos', ['numero_processo' => 'P0100']);
        $response->assertRedirect();
    }

    public function test_professor_nao_pode_criar_aluno_em_turma_que_nao_lecciona(): void
    {
        $professor = User::factory()->professor()->create();
        $outraTurma = Turma::factory()->create();

        $response = $this->actingAs($professor)->post('/alunos', [
            'numero_processo' => 'P0101',
            'nome' => 'Aluno Indevido',
            'turma_id' => $outraTurma->id,
            'ativo' => '1',
        ]);

        $response->assertSessionHasErrors('turma_id');
        $this->assertDatabaseMissing('alunos', ['numero_processo' => 'P0101']);
    }

    public function test_professor_nao_ve_alunos_de_outras_turmas(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach($professor->id);

        $alunoDaTurma = Aluno::factory()->create(['turma_id' => $turma->id]);
        $alunoDeOutraTurma = Aluno::factory()->create();

        $response = $this->actingAs($professor)->get('/alunos');

        $response->assertSee($alunoDaTurma->nome);
        $response->assertDontSee($alunoDeOutraTurma->nome);
    }

    public function test_professor_nao_pode_ver_aluno_de_outra_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($professor)->get("/alunos/{$aluno->id}");

        $response->assertForbidden();
    }

    public function test_apenas_direcao_pode_eliminar_aluno(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach($professor->id);
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);

        $response = $this->actingAs($professor)->delete("/alunos/{$aluno->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('alunos', ['id' => $aluno->id]);
    }
}
