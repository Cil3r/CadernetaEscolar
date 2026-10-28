<?php

namespace Tests\Feature;

use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurmaTest extends TestCase
{
    use RefreshDatabase;

    public function test_direcao_pode_criar_turma(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->post('/turmas', [
            'nome' => '10.ª A',
            'classe' => '10.ª Classe',
            'curso' => 'Informática',
            'ano_letivo' => '2026/2027',
            'periodo' => 'manha',
        ]);

        $this->assertDatabaseHas('turmas', ['nome' => '10.ª A']);
        $response->assertRedirect();
    }

    public function test_professor_nao_pode_criar_turma(): void
    {
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($professor)->post('/turmas', [
            'nome' => '10.ª A',
            'classe' => '10.ª Classe',
            'ano_letivo' => '2026/2027',
            'periodo' => 'manha',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('turmas', ['nome' => '10.ª A']);
    }

    public function test_professor_so_ve_as_suas_turmas_na_lista(): void
    {
        $professor = User::factory()->professor()->create();
        $turmaDoProfessor = Turma::factory()->create();
        $outraTurma = Turma::factory()->create();

        $turmaDoProfessor->professores()->attach($professor->id, ['disciplina' => 'Matemática']);

        $response = $this->actingAs($professor)->get('/turmas');

        $response->assertStatus(200);
        $response->assertSee($turmaDoProfessor->nome);
        $response->assertDontSee($outraTurma->nome);
    }

    public function test_professor_nao_pode_ver_turma_que_nao_lecciona(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();

        $response = $this->actingAs($professor)->get("/turmas/{$turma->id}");

        $response->assertForbidden();
    }

    public function test_direcao_pode_associar_professor_a_turma(): void
    {
        $direcao = User::factory()->direcao()->create();
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();

        $response = $this->actingAs($direcao)->post("/turmas/{$turma->id}/professores", [
            'user_id' => $professor->id,
            'disciplina' => 'Física',
        ]);

        $response->assertRedirect(route('turmas.show', $turma));
        $this->assertDatabaseHas('turma_professor', [
            'turma_id' => $turma->id,
            'user_id' => $professor->id,
            'disciplina' => 'Física',
        ]);
    }

    public function test_direcao_pode_eliminar_turma(): void
    {
        $direcao = User::factory()->direcao()->create();
        $turma = Turma::factory()->create();

        $response = $this->actingAs($direcao)->delete("/turmas/{$turma->id}");

        $response->assertRedirect(route('turmas.index'));
        $this->assertDatabaseMissing('turmas', ['id' => $turma->id]);
    }
}
