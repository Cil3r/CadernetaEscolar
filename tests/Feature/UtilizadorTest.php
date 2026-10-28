<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilizadorTest extends TestCase
{
    use RefreshDatabase;

    public function test_direcao_pode_listar_utilizadores(): void
    {
        $direcao = User::factory()->direcao()->create();
        User::factory()->professor()->create(['name' => 'Professor Exemplo']);

        $response = $this->actingAs($direcao)->get('/utilizadores');

        $response->assertStatus(200);
        $response->assertSee('Professor Exemplo');
    }

    public function test_professor_nao_pode_acessar_utilizadores(): void
    {
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($professor)->get('/utilizadores');

        $response->assertForbidden();
    }

    public function test_direcao_pode_criar_utilizador(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->post('/utilizadores', [
            'name' => 'Nova Professora',
            'email' => 'nova.professora@caderneta.ao',
            'password' => 'password123',
            'perfil' => 'professor',
            'ativo' => '1',
        ]);

        $response->assertRedirect(route('utilizadores.index'));
        $this->assertDatabaseHas('users', ['email' => 'nova.professora@caderneta.ao', 'perfil' => 'professor']);
    }

    public function test_direcao_nao_pode_eliminar_a_propria_conta(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->delete("/utilizadores/{$direcao->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $direcao->id]);
    }

    public function test_direcao_pode_eliminar_outro_utilizador(): void
    {
        $direcao = User::factory()->direcao()->create();
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($direcao)->delete("/utilizadores/{$professor->id}");

        $response->assertRedirect(route('utilizadores.index'));
        $this->assertDatabaseMissing('users', ['id' => $professor->id]);
    }
}
