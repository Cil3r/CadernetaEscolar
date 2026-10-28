<?php

namespace App\Http\Controllers;

use App\Http\Requests\Utilizador\StoreUtilizadorRequest;
use App\Http\Requests\Utilizador\UpdateUtilizadorRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UtilizadorController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $utilizadores = User::orderBy('name')->paginate(10);

        return view('utilizadores.index', ['utilizadores' => $utilizadores]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('utilizadores.create');
    }

    public function store(StoreUtilizadorRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'ativo' => $request->boolean('ativo', true),
            'password' => $request->validated('password'),
        ]);

        return redirect()->route('utilizadores.index')->with('sucesso', 'Utilizador criado com sucesso.');
    }

    public function edit(User $utilizador): View
    {
        $this->authorize('update', $utilizador);

        return view('utilizadores.edit', ['utilizador' => $utilizador]);
    }

    public function update(UpdateUtilizadorRequest $request, User $utilizador): RedirectResponse
    {
        $dados = $request->validated();

        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        $dados['ativo'] = $request->boolean('ativo');

        $utilizador->update($dados);

        return redirect()->route('utilizadores.index')->with('sucesso', 'Utilizador atualizado com sucesso.');
    }

    public function destroy(User $utilizador): RedirectResponse
    {
        $this->authorize('delete', $utilizador);

        $utilizador->delete();

        return redirect()->route('utilizadores.index')->with('sucesso', 'Utilizador eliminado com sucesso.');
    }
}
