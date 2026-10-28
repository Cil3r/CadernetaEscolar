<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Falta;
use App\Models\Ocorrencia;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $request->validate([
            'de' => ['nullable', 'date'],
            'ate' => ['nullable', 'date'],
        ], [
            'de.date' => 'A data inicial indicada é inválida.',
            'ate.date' => 'A data final indicada é inválida.',
        ]);

        $hoje = now()->startOfDay();
        $ate = $request->filled('ate') ? Carbon::parse($request->query('ate'))->startOfDay() : $hoje->copy();
        $de = $request->filled('de') ? Carbon::parse($request->query('de'))->startOfDay() : $hoje->copy()->subDays(29);

        if ($de->greaterThan($ate)) {
            [$de, $ate] = [$ate->copy(), $de->copy()];
        }

        $user = $request->user();

        $turmasQuery = Turma::query();
        $alunosQuery = Aluno::query();
        $ocorrenciasQuery = Ocorrencia::query()
            ->whereDate('data', '>=', $de->toDateString())
            ->whereDate('data', '<=', $ate->toDateString());
        $faltasQuery = Falta::query()
            ->whereDate('data', '>=', $de->toDateString())
            ->whereDate('data', '<=', $ate->toDateString());

        if (! $user->isGestor()) {
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

        $alunosBase = $user->isGestor() ? Aluno::query() : Aluno::query()->whereIn('turma_id', $user->turmas()->pluck('turmas.id'));

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
            'de' => $de,
            'ate' => $ate,
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
