<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Configuracao;
use Illuminate\View\View;

class CadernetaController extends Controller
{
    public function show(Aluno $aluno): View
    {
        $this->authorize('view', $aluno);

        $aluno->load([
            'turma',
            'ocorrencias' => fn ($q) => $q->with('autor')->orderByDesc('data'),
            'faltas' => fn ($q) => $q->with('autor')->orderByDesc('data'),
        ]);

        $eventos = $aluno->ocorrencias->map(fn ($ocorrencia) => [
            'tipo' => 'ocorrencia',
            'data' => $ocorrencia->data,
            'modelo' => $ocorrencia,
        ])->concat($aluno->faltas->map(fn ($falta) => [
            'tipo' => 'falta',
            'data' => $falta->data,
            'modelo' => $falta,
        ]))->sortByDesc('data')->values();

        $limiteFaltas = (int) Configuracao::obter('limite_faltas', 10);
        $limiteGravidade = (int) Configuracao::obter('limite_gravidade', 4);

        $totalFaltasInjustificadas = $aluno->faltas->where('justificada', false)->count();
        $ocorrenciasGraves = $aluno->ocorrencias->where('gravidade', '>=', $limiteGravidade)->count();

        return view('alunos.caderneta', [
            'aluno' => $aluno,
            'eventos' => $eventos,
            'resumo' => [
                'total_ocorrencias' => $aluno->ocorrencias->count(),
                'total_faltas' => $aluno->faltas->count(),
                'faltas_justificadas' => $aluno->faltas->where('justificada', true)->count(),
                'faltas_injustificadas' => $totalFaltasInjustificadas,
            ],
            'alertas' => [
                'faltas' => $totalFaltasInjustificadas >= $limiteFaltas,
                'gravidade' => $ocorrenciasGraves > 0,
            ],
            'limites' => [
                'faltas' => $limiteFaltas,
                'gravidade' => $limiteGravidade,
            ],
        ]);
    }
}
