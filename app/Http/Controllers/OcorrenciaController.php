<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ocorrencia\StoreOcorrenciaRequest;
use App\Http\Requests\Ocorrencia\UpdateOcorrenciaRequest;
use App\Models\Aluno;
use App\Models\Ocorrencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OcorrenciaController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Ocorrencia::query()->with(['aluno.turma', 'autor'])->orderByDesc('data');

        if (! $user->isGestor()) {
            $query->whereHas('aluno', fn ($q) => $q->whereIn('turma_id', $user->turmas()->pluck('turmas.id')));
        }

        if ($request->filled('aluno_id')) {
            $query->where('aluno_id', $request->integer('aluno_id'));
        }

        if ($request->filled('gravidade')) {
            $query->where('gravidade', $request->integer('gravidade'));
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->string('tipo'));
        }

        if ($request->filled('de')) {
            $query->whereDate('data', '>=', $request->date('de'));
        }

        if ($request->filled('ate')) {
            $query->whereDate('data', '<=', $request->date('ate'));
        }

        $alunosDisponiveis = $this->alunosDisponiveis($user);

        return view('ocorrencias.index', [
            'ocorrencias' => $query->paginate(10)->withQueryString(),
            'alunosDisponiveis' => $alunosDisponiveis,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Ocorrencia::class);

        return view('ocorrencias.create', [
            'alunosDisponiveis' => $this->alunosDisponiveis($request->user()),
            'alunoSelecionado' => $request->integer('aluno_id') ?: null,
        ]);
    }

    public function store(StoreOcorrenciaRequest $request): RedirectResponse
    {
        Ocorrencia::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('ocorrencias.index')->with('sucesso', 'Ocorrência registada com sucesso.');
    }

    public function edit(Ocorrencia $ocorrencia): View
    {
        $this->authorize('update', $ocorrencia);

        return view('ocorrencias.edit', ['ocorrencia' => $ocorrencia->load('aluno')]);
    }

    public function update(UpdateOcorrenciaRequest $request, Ocorrencia $ocorrencia): RedirectResponse
    {
        $ocorrencia->update($request->validated());

        return redirect()->route('ocorrencias.index')->with('sucesso', 'Ocorrência atualizada com sucesso.');
    }

    public function destroy(Ocorrencia $ocorrencia): RedirectResponse
    {
        $this->authorize('delete', $ocorrencia);

        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('sucesso', 'Ocorrência eliminada com sucesso.');
    }

    private function alunosDisponiveis($user)
    {
        $query = Aluno::query()->orderBy('nome');

        if (! $user->isGestor()) {
            $query->whereIn('turma_id', $user->turmas()->pluck('turmas.id'));
        }

        return $query->get();
    }
}
