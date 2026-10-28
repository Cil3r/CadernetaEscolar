<?php

namespace App\Http\Controllers;

use App\Http\Requests\Aluno\StoreAlunoRequest;
use App\Http\Requests\Aluno\UpdateAlunoRequest;
use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlunoController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Aluno::query()->with('turma')->orderBy('nome');

        if (! $user->isDirecao()) {
            $query->whereIn('turma_id', $user->turmas()->pluck('turmas.id'));
        }

        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->integer('turma_id'));
        }

        if ($request->filled('pesquisa')) {
            $pesquisa = $request->string('pesquisa');
            $query->where(fn ($q) => $q->where('nome', 'like', "%{$pesquisa}%")
                ->orWhere('numero_processo', 'like', "%{$pesquisa}%"));
        }

        $turmasDisponiveis = $user->isDirecao()
            ? Turma::orderBy('nome')->get()
            : $user->turmas()->orderBy('nome')->get();

        return view('alunos.index', [
            'alunos' => $query->paginate(10)->withQueryString(),
            'turmasDisponiveis' => $turmasDisponiveis,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Aluno::class);

        $turmasDisponiveis = $request->user()->isDirecao()
            ? Turma::orderBy('nome')->get()
            : $request->user()->turmas()->orderBy('nome')->get();

        return view('alunos.create', ['turmasDisponiveis' => $turmasDisponiveis]);
    }

    public function store(StoreAlunoRequest $request): RedirectResponse
    {
        $aluno = Aluno::create([
            ...$request->validated(),
            'ativo' => $request->boolean('ativo', true),
        ]);

        return redirect()->route('alunos.show', $aluno)->with('sucesso', 'Aluno criado com sucesso.');
    }

    public function show(Aluno $aluno): View
    {
        $this->authorize('view', $aluno);

        $aluno->load('turma');

        return view('alunos.show', ['aluno' => $aluno]);
    }

    public function edit(Request $request, Aluno $aluno): View
    {
        $this->authorize('update', $aluno);

        $turmasDisponiveis = $request->user()->isDirecao()
            ? Turma::orderBy('nome')->get()
            : $request->user()->turmas()->orderBy('nome')->get();

        return view('alunos.edit', ['aluno' => $aluno, 'turmasDisponiveis' => $turmasDisponiveis]);
    }

    public function update(UpdateAlunoRequest $request, Aluno $aluno): RedirectResponse
    {
        $aluno->update([
            ...$request->validated(),
            'ativo' => $request->boolean('ativo'),
        ]);

        return redirect()->route('alunos.show', $aluno)->with('sucesso', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno): RedirectResponse
    {
        $this->authorize('delete', $aluno);

        $aluno->delete();

        return redirect()->route('alunos.index')->with('sucesso', 'Aluno eliminado com sucesso.');
    }
}
