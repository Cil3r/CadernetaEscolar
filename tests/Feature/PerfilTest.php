<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_qualquer_utilizador_pode_ver_o_seu_perfil(): void
    {
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($professor)->get('/perfil');

        $response->assertStatus(200);
        $response->assertSee($professor->name);
    }

    public function test_utilizador_pode_atualizar_o_nome(): void
    {
        $professor = User::factory()->professor()->create();

        $response = $this->actingAs($professor)->put('/perfil', [
            'name' => 'Nome Atualizado',
        ]);

        $response->assertRedirect(route('perfil.edit'));
        $this->assertDatabaseHas('users', ['id' => $professor->id, 'name' => 'Nome Atualizado']);
    }

    public function test_utilizador_pode_fazer_upload_de_foto_de_perfil(): void
    {
        Storage::fake('public');

        $direcao = User::factory()->direcao()->create();
        $ficheiro = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($direcao)->put('/perfil', [
            'name' => $direcao->name,
            'foto' => $ficheiro,
        ]);

        $response->assertRedirect(route('perfil.edit'));
        $direcao->refresh();
        $this->assertNotNull($direcao->foto);
        Storage::disk('public')->assertExists($direcao->foto);
    }

    public function test_foto_invalida_e_rejeitada(): void
    {
        Storage::fake('public');

        $professor = User::factory()->professor()->create();
        $ficheiro = UploadedFile::fake()->create('documento.pdf', 100);

        $response = $this->actingAs($professor)->put('/perfil', [
            'name' => $professor->name,
            'foto' => $ficheiro,
        ]);

        $response->assertSessionHasErrors('foto');
    }
}
