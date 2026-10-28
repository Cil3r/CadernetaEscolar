<?php

namespace App\Http\Controllers;

use App\Http\Requests\Configuracao\UpdateConfiguracaoRequest;
use App\Models\Configuracao;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConfiguracaoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Configuracao::class);

        return view('configuracoes.index', [
            'limiteFaltas' => Configuracao::obter('limite_faltas', 10),
            'limiteGravidade' => Configuracao::obter('limite_gravidade', 4),
        ]);
    }

    public function update(UpdateConfiguracaoRequest $request): RedirectResponse
    {
        Configuracao::definir('limite_faltas', $request->validated('limite_faltas'));
        Configuracao::definir('limite_gravidade', $request->validated('limite_gravidade'));

        return redirect()->route('configuracoes.index')->with('sucesso', 'Configurações atualizadas com sucesso.');
    }
}
