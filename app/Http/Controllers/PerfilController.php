<?php

namespace App\Http\Controllers;

use App\Http\Requests\Perfil\UpdatePerfilRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('perfil.edit', ['user' => $request->user()]);
    }

    public function update(UpdatePerfilRequest $request): RedirectResponse
    {
        $user = $request->user();

        $dados = ['name' => $request->validated('name')];

        if ($request->filled('password')) {
            $dados['password'] = $request->validated('password');
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $dados['foto'] = $request->file('foto')->store('avatares', 'public');
        }

        $user->update($dados);

        return redirect()->route('perfil.edit')->with('sucesso', 'Perfil atualizado com sucesso.');
    }
}
