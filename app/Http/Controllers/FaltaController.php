<?php

namespace App\Http\Controllers;

use App\Http\Requests\Falta\StoreFaltaRequest;
use App\Http\Requests\Falta\UpdateFaltaRequest;
use App\Models\Aluno;
use App\Models\Falta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaltaController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Falta::query()->with(['aluno.turma', 'autor'])->orderByDesc('data');

        if (! $user->isDirecao()) {
            $query->whereHas('aluno', fn ($q) => $q->whereIn('turma_id', $user->turmas()->pluck('turmas.id')));
        }

        if ($request->filled('aluno_id')) {
            $query->where('aluno_id', $request->integer('aluno_id'));
        }

        if ($request->filled('estado')) {
            $query->where('justificada', $request->string('estado') === 'justificada');
        }

        if ($request->filled('de')) {
            $query->whereDate('data', '>=', $request->date('de'));
        }

        if ($request->filled('ate')) {
            $query->whereDate('data', '<=', $request->date('ate'));
        }

        $totais = [
            'total' => (clone $query)->count(),
            'justificadas' => (clone $query)->where('justificada', true)->count(),
            'injustificadas' => (clone $query)->where('justificada', false)->count(),
        ];

        return view('faltas.index', [
            'faltas' => $query->paginate(10)->withQueryString(),
            'alunosDisponiveis' => $this->alunosDisponiveis($user),
            'totais' => $totais,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Falta::class);

        return view('faltas.create', [
            'alunosDisponiveis' => $this->alunosDisponiveis($request->user()),
            'alunoSelecionado' => $request->integer('aluno_id') ?: null,
        ]);
    }

    public function store(StoreFaltaRequest $request): RedirectResponse
    {
        Falta::create([
            ...$request->validated(),
            'justificada' => $request->boolean('justificada'),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('faltas.index')->with('sucesso', 'Falta registada com sucesso.');
    }

    public function edit(Falta $falta): View
    {
        $this->authorize('update', $falta);

        return view('faltas.edit', ['falta' => $falta->load('aluno')]);
    }

    public function update(UpdateFaltaRequest $request, Falta $falta): RedirectResponse
    {
        $falta->update([
            ...$request->validated(),
            'justificada' => $request->boolean('justificada'),
        ]);

        return redirect()->route('faltas.index')->with('sucesso', 'Falta atualizada com sucesso.');
    }

    public function destroy(Falta $falta): RedirectResponse
    {
        $this->authorize('delete', $falta);

        $falta->delete();

        return redirect()->route('faltas.index')->with('sucesso', 'Falta eliminada com sucesso.');
    }

    private function alunosDisponiveis($user)
    {
        $query = Aluno::query()->orderBy('nome');

        if (! $user->isDirecao()) {
            $query->whereIn('turma_id', $user->turmas()->pluck('turmas.id'));
        }

        return $query->get();
    }
}
