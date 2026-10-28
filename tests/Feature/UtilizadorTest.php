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

    public function test_direcao_nao_pode_gerir_outra_conta_de_direcao(): void
    {
        $direcao = User::factory()->direcao()->create();
        $outraDirecao = User::factory()->direcao()->create();

        $this->actingAs($direcao)->get("/utilizadores/{$outraDirecao->id}/edit")->assertForbidden();
        $this->actingAs($direcao)->delete("/utilizadores/{$outraDirecao->id}")->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $outraDirecao->id]);
    }

    public function test_direcao_nao_pode_criar_conta_de_direcao_ou_super_admin(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->post('/utilizadores', [
            'name' => 'Tentativa',
            'email' => 'tentativa@caderneta.ao',
            'password' => 'password123',
            'perfil' => 'direcao',
            'ativo' => '1',
        ]);

        $response->assertSessionHasErrors('perfil');
        $this->assertDatabaseMissing('users', ['email' => 'tentativa@caderneta.ao']);
    }

    public function test_super_admin_pode_gerir_qualquer_conta(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $direcao = User::factory()->direcao()->create();

        $resposta = $this->actingAs($superAdmin)->put("/utilizadores/{$direcao->id}", [
            'name' => $direcao->name,
            'email' => $direcao->email,
            'perfil' => 'direcao',
            'ativo' => '1',
        ]);
        $resposta->assertRedirect(route('utilizadores.index'));

        $this->actingAs($superAdmin)->delete("/utilizadores/{$direcao->id}")->assertRedirect(route('utilizadores.index'));
        $this->assertDatabaseMissing('users', ['id' => $direcao->id]);
    }

    public function test_lista_de_direcao_mostra_apenas_professores(): void
    {
        $direcao = User::factory()->direcao()->create();
        $outraDirecao = User::factory()->direcao()->create(['name' => 'Outra Direção']);

        $response = $this->actingAs($direcao)->get('/utilizadores');

        $response->assertDontSee('Outra Direção');
    }
}
