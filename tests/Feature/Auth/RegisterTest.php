<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_de_registo_e_visivel(): void
    {
        $response = $this->get('/registar');

        $response->assertStatus(200);
    }

    public function test_pode_criar_conta_como_professor_inativa(): void
    {
        $response = $this->post('/registar', [
            'name' => 'Novo Professor',
            'email' => 'novo.professor@caderneta.ao',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'novo.professor@caderneta.ao',
            'perfil' => 'professor',
            'ativo' => false,
        ]);
    }

    public function test_conta_recem_criada_nao_pode_entrar_antes_de_ativada(): void
    {
        $this->post('/registar', [
            'name' => 'Novo Professor',
            'email' => 'novo.professor@caderneta.ao',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'novo.professor@caderneta.ao',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_nao_pode_registar_com_email_ja_existente(): void
    {
        User::factory()->create(['email' => 'existente@caderneta.ao']);

        $response = $this->post('/registar', [
            'name' => 'Outro',
            'email' => 'existente@caderneta.ao',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_senhas_diferentes_sao_rejeitadas(): void
    {
        $response = $this->post('/registar', [
            'name' => 'Novo Professor',
            'email' => 'novo.professor@caderneta.ao',
            'password' => 'password123',
            'password_confirmation' => 'outra-senha',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
