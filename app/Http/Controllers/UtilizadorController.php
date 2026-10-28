<?php

namespace App\Http\Controllers;

use App\Http\Requests\Utilizador\StoreUtilizadorRequest;
use App\Http\Requests\Utilizador\UpdateUtilizadorRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UtilizadorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $query = User::orderBy('name');

        if (! $request->user()->isSuperAdmin()) {
            $query->where('perfil', 'professor');
        }

        return view('utilizadores.index', ['utilizadores' => $query->paginate(10)]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('utilizadores.create');
    }

    public function store(StoreUtilizadorRequest $request): RedirectResponse
    {
        $dados = $request->validated();
        $dados['ativo'] = $request->boolean('ativo', true);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('avatares', 'public');
        }

        User::create($dados);

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

        if ($request->hasFile('foto')) {
            if ($utilizador->foto) {
                Storage::disk('public')->delete($utilizador->foto);
            }

            $dados['foto'] = $request->file('foto')->store('avatares', 'public');
        } else {
            unset($dados['foto']);
        }

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
