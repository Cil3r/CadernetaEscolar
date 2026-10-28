<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Falta;
use App\Models\Ocorrencia;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $desde = now()->subDays(30);

        $turmasQuery = Turma::query();
        $alunosQuery = Aluno::query();
        $ocorrenciasQuery = Ocorrencia::query()->where('data', '>=', $desde);
        $faltasQuery = Falta::query()->where('data', '>=', $desde);

        if (! $user->isDirecao()) {
            $turmaIds = $user->turmas()->pluck('turmas.id');
            $turmasQuery->whereKey($turmaIds);
            $alunosQuery->whereIn('turma_id', $turmaIds);
            $ocorrenciasQuery->whereHas('aluno', fn ($q) => $q->whereIn('turma_id', $turmaIds));
            $faltasQuery->whereHas('aluno', fn ($q) => $q->whereIn('turma_id', $turmaIds));
        }

        $ocorrenciasPorGravidade = (clone $ocorrenciasQuery)
            ->select('gravidade', DB::raw('count(*) as total'))
            ->groupBy('gravidade')
            ->pluck('total', 'gravidade');

        $limiteFaltas = (int) Configuracao::obter('limite_faltas', 10);
        $limiteGravidade = (int) Configuracao::obter('limite_gravidade', 4);

        $alunosBase = $user->isDirecao() ? Aluno::query() : Aluno::query()->whereIn('turma_id', $user->turmas()->pluck('turmas.id'));

        $alunosEmAlerta = (clone $alunosBase)
            ->with('turma')
            ->get()
            ->filter(function (Aluno $aluno) use ($limiteFaltas, $limiteGravidade) {
                $faltasInjustificadas = $aluno->faltas()->where('justificada', false)->count();
                $ocorrenciasGraves = $aluno->ocorrencias()->where('gravidade', '>=', $limiteGravidade)->count();

                return $faltasInjustificadas >= $limiteFaltas || $ocorrenciasGraves > 0;
            })
            ->values();

        return view('dashboard', [
            'user' => $user,
            'totalTurmas' => (clone $turmasQuery)->count(),
            'totalAlunos' => (clone $alunosQuery)->where('ativo', true)->count(),
            'totalOcorrencias' => (clone $ocorrenciasQuery)->count(),
            'totalFaltas' => (clone $faltasQuery)->count(),
            'alunosEmAlerta' => $alunosEmAlerta,
            'dadosGraficos' => [
                'ocorrenciasPorGravidade' => collect(range(1, 5))->map(fn ($g) => $ocorrenciasPorGravidade->get($g, 0)),
                'faltasJustificadas' => (clone $faltasQuery)->where('justificada', true)->count(),
                'faltasInjustificadas' => (clone $faltasQuery)->where('justificada', false)->count(),
            ],
        ]);
    }
}
