<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_de_login_e_visivel(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_utilizador_direcao_pode_autenticar_se(): void
    {
        $user = User::factory()->direcao()->create([
            'email' => 'direcao@caderneta.ao',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'direcao@caderneta.ao',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_utilizador_professor_pode_autenticar_se(): void
    {
        $user = User::factory()->professor()->create([
            'email' => 'professor@caderneta.ao',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'professor@caderneta.ao',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_credenciais_invalidas_nao_autenticam(): void
    {
        User::factory()->create([
            'email' => 'direcao@caderneta.ao',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'direcao@caderneta.ao',
            'password' => 'senha-errada',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_utilizador_inativo_nao_consegue_entrar(): void
    {
        User::factory()->inativo()->create([
            'email' => 'inativo@caderneta.ao',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'inativo@caderneta.ao',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_utilizador_autenticado_pode_sair(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
