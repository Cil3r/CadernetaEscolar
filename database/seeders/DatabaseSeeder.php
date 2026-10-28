<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Falta;
use App\Models\Ocorrencia;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $direcao = User::create([
            'name' => 'Direção Geral',
            'email' => 'direcao@caderneta.ao',
            'password' => 'password',
            'perfil' => 'direcao',
        ]);

        $professor = User::create([
            'name' => 'Professor Teste',
            'email' => 'professor@caderneta.ao',
            'password' => 'password',
            'perfil' => 'professor',
        ]);

        $turma = Turma::create([
            'nome' => '10.ª A',
            'classe' => '10.ª Classe',
            'curso' => 'Informática',
            'ano_letivo' => '2026/2027',
            'periodo' => 'manha',
        ]);

        $turma->professores()->attach($professor->id, ['disciplina' => 'Matemática']);

        $nomes = ['Ana Paulo', 'Bruno Manuel', 'Carla Domingos', 'David Neto', 'Elisa Gomes'];

        foreach ($nomes as $i => $nome) {
            $aluno = Aluno::create([
                'numero_processo' => 'P'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nome' => $nome,
                'data_nascimento' => '2010-0'.($i + 1).'-15',
                'genero' => $i % 2 === 0 ? 'F' : 'M',
                'encarregado_nome' => 'Encarregado de '.$nome,
                'encarregado_contacto' => '9230000'.str_pad($i, 2, '0', STR_PAD_LEFT),
                'turma_id' => $turma->id,
            ]);

            Ocorrencia::create([
                'aluno_id' => $aluno->id,
                'user_id' => $professor->id,
                'data' => now()->subDays($i + 1),
                'tipo' => 'comportamento',
                'descricao' => 'Ocorrência de exemplo para '.$nome,
                'gravidade' => ($i % 5) + 1,
            ]);

            Falta::create([
                'aluno_id' => $aluno->id,
                'user_id' => $professor->id,
                'data' => now()->subDays($i + 2),
                'justificada' => $i % 2 === 0,
                'motivo' => $i % 2 === 0 ? 'Doença' : null,
            ]);
        }

        Configuracao::insert([
            ['chave' => 'limite_faltas', 'valor' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['chave' => 'limite_gravidade', 'valor' => '4', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
