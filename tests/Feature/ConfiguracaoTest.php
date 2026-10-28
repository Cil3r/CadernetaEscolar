<?php

namespace Tests\Feature;

use App\Models\Configuracao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfiguracaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_direcao_pode_ver_e_atualizar_configuracoes(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->put('/configuracoes', [
            'limite_faltas' => 15,
            'limite_gravidade' => 5,
        ]);

        $response->assertRedirect(route('configuracoes.index'));
        $this->assertEquals('15', Configuracao::obter('limite_faltas'));
        $this->assertEquals('5', Configuracao::obter('limite_gravidade'));
    }

    public function test_professor_nao_pode_acessar_configuracoes(): void
    {
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($professor)->get('/configuracoes');

        $response->assertForbidden();
    }

    public function test_validacao_rejeita_limiar_de_gravidade_invalido(): void
    {
        $direcao = User::factory()->direcao()->create();

        $response = $this->actingAs($direcao)->put('/configuracoes', [
            'limite_faltas' => 10,
            'limite_gravidade' => 9,
        ]);

        $response->assertSessionHasErrors('limite_gravidade');
    }
}
