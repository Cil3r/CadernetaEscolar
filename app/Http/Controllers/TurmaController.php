<?php

namespace App\Http\Controllers;

use App\Http\Requests\Turma\AssociarProfessorRequest;
use App\Http\Requests\Turma\StoreTurmaRequest;
use App\Http\Requests\Turma\UpdateTurmaRequest;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TurmaController extends Controller
{
    public function index(Request $request): View
    {
        $turmas = $request->user()->isDirecao()
            ? Turma::withCount('alunos')->orderBy('nome')->paginate(10)
            : $request->user()->turmas()->withCount('alunos')->orderBy('nome')->paginate(10);

        return view('turmas.index', ['turmas' => $turmas]);
    }

    public function create(): View
    {
        $this->authorize('create', Turma::class);

        return view('turmas.create');
    }

    public function store(StoreTurmaRequest $request): RedirectResponse
    {
        $turma = Turma::create($request->validated());

        return redirect()->route('turmas.show', $turma)->with('sucesso', 'Turma criada com sucesso.');
    }

    public function show(Turma $turma): View
    {
        $this->authorize('view', $turma);

        $turma->load(['alunos' => fn ($query) => $query->orderBy('nome'), 'professores']);

        $professoresDisponiveis = User::where('perfil', 'professor')
            ->whereNotIn('id', $turma->professores->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('turmas.show', [
            'turma' => $turma,
            'professoresDisponiveis' => $professoresDisponiveis,
        ]);
    }

    public function edit(Turma $turma): View
    {
        $this->authorize('update', $turma);

        return view('turmas.edit', ['turma' => $turma]);
    }

    public function update(UpdateTurmaRequest $request, Turma $turma): RedirectResponse
    {
        $turma->update($request->validated());

        return redirect()->route('turmas.show', $turma)->with('sucesso', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma): RedirectResponse
    {
        $this->authorize('delete', $turma);

        $turma->delete();

        return redirect()->route('turmas.index')->with('sucesso', 'Turma eliminada com sucesso.');
    }

    public function associarProfessor(AssociarProfessorRequest $request, Turma $turma): RedirectResponse
    {
        $turma->professores()->syncWithoutDetaching([
            $request->validated('user_id') => ['disciplina' => $request->validated('disciplina')],
        ]);

        return redirect()->route('turmas.show', $turma)->with('sucesso', 'Professor associado à turma.');
    }

    public function removerProfessor(Turma $turma, User $professor): RedirectResponse
    {
        $this->authorize('update', $turma);

        $turma->professores()->detach($professor->id);

        return redirect()->route('turmas.show', $turma)->with('sucesso', 'Professor removido da turma.');
    }
}
