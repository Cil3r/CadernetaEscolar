@extends('layouts.app')

@section('titulo', $turma->nome)

@section('conteudo')
    <x-cabecalho-pagina :titulo="$turma->nome" descricao="{{ $turma->classe }} · {{ $turma->ano_letivo }} · {{ ucfirst($turma->periodo) }}">
        @can('update', $turma)
            <x-botao-link variante="secundario" href="{{ route('turmas.edit', $turma) }}">Editar</x-botao-link>
        @endcan
        @can('delete', $turma)
            <form method="POST" action="{{ route('turmas.destroy', $turma) }}" onsubmit="return confirm('Eliminar esta turma?');">
                @csrf
                @method('DELETE')
                <x-botao variante="perigo" tipo="submit">Eliminar</x-botao>
            </form>
        @endcan
    </x-cabecalho-pagina>

    <x-alerta />

    <div class="grid gap-6 lg:grid-cols-2">
        <x-cartao titulo="Alunos ({{ $turma->alunos->count() }})">
            @if ($turma->alunos->isEmpty())
                <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Ainda não há alunos nesta turma.</p>
            @else
                <ul class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($turma->alunos as $aluno)
                        <li class="flex items-center justify-between py-2 text-sm">
                            <a href="{{ route('alunos.caderneta', $aluno) }}" class="text-marinho-900 hover:underline dark:text-white">{{ $aluno->nome }}</a>
                            <span class="text-marinho-600 dark:text-azul-claro-200">{{ $aluno->numero_processo }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-cartao>

        <x-cartao titulo="Professores">
            @if ($turma->professores->isEmpty())
                <p class="mb-4 text-sm text-marinho-600 dark:text-azul-claro-200">Ainda não há professores associados.</p>
            @else
                <ul class="mb-4 divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($turma->professores as $professor)
                        <li class="flex items-center justify-between py-2 text-sm">
                            <span class="text-marinho-900 dark:text-white">
                                {{ $professor->name }}
                                @if ($professor->pivot->disciplina)
                                    <span class="text-marinho-600 dark:text-azul-claro-200">· {{ $professor->pivot->disciplina }}</span>
                                @endif
                            </span>
                            @can('update', $turma)
                                <form method="POST" action="{{ route('turmas.professores.remover', [$turma, $professor]) }}" onsubmit="return confirm('Remover este professor da turma?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:underline dark:text-red-400">Remover</button>
                                </form>
                            @endcan
                        </li>
                    @endforeach
                </ul>
            @endif

            @can('update', $turma)
                <form method="POST" action="{{ route('turmas.professores.associar', $turma) }}" class="flex flex-wrap items-end gap-2">
                    @csrf
                    <div class="flex-1">
                        <label for="user_id" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Professor</label>
                        <select id="user_id" name="user_id" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                            @foreach ($professoresDisponiveis as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="disciplina" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Disciplina</label>
                        <input id="disciplina" name="disciplina" type="text" placeholder="Matemática" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                    </div>
                    <x-botao tipo="submit">Associar</x-botao>
                </form>
            @endcan
        </x-cartao>
    </div>
@endsection
