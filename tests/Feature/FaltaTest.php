<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Falta;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaltaTest extends TestCase
{
    use RefreshDatabase;

    public function test_professor_pode_registar_falta_para_aluno_da_sua_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach($professor->id);
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);

        $response = $this->actingAs($professor)->post('/faltas', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'justificada' => '0',
        ]);

        $response->assertRedirect(route('faltas.index'));
        $this->assertDatabaseHas('faltas', ['aluno_id' => $aluno->id, 'justificada' => false]);
    }

    public function test_falta_justificada_exige_motivo(): void
    {
        $direcao = User::factory()->direcao()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($direcao)->post('/faltas', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'justificada' => '1',
        ]);

        $response->assertSessionHasErrors('motivo');
    }

    public function test_professor_nao_pode_registar_falta_para_aluno_de_outra_turma(): void
    {
        $professor = User::factory()->professor()->create();
        $aluno = Aluno::factory()->create();

        $response = $this->actingAs($professor)->post('/faltas', [
            'aluno_id' => $aluno->id,
            'data' => now()->format('Y-m-d'),
            'justificada' => '0',
        ]);

        $response->assertSessionHasErrors('aluno_id');
    }

    public function test_contagens_de_faltas_sao_calculadas_corretamente(): void
    {
        $direcao = User::factory()->direcao()->create();
        $aluno = Aluno::factory()->create();
        Falta::factory()->count(2)->create(['aluno_id' => $aluno->id, 'justificada' => true, 'motivo' => 'Doença']);
        Falta::factory()->count(3)->create(['aluno_id' => $aluno->id, 'justificada' => false, 'motivo' => null]);

        $response = $this->actingAs($direcao)->get('/faltas');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Total de faltas', '5']);
        $response->assertSeeInOrder(['Justificadas', '2']);
        $response->assertSeeInOrder(['Injustificadas', '3']);
    }

    public function test_apenas_autor_ou_direcao_pode_editar_falta(): void
    {
        $professor = User::factory()->professor()->create();
        $outroProfessor = User::factory()->professor()->create();
        $turma = Turma::factory()->create();
        $turma->professores()->attach([$professor->id, $outroProfessor->id]);
        $aluno = Aluno::factory()->create(['turma_id' => $turma->id]);
        $falta = Falta::factory()->create(['aluno_id' => $aluno->id, 'user_id' => $outroProfessor->id, 'justificada' => false]);

        $response = $this->actingAs($professor)->put("/faltas/{$falta->id}", [
            'data' => now()->format('Y-m-d'),
            'justificada' => '0',
        ]);

        $response->assertForbidden();
    }
}
