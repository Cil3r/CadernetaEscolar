@extends('layouts.app')

@section('titulo', 'Ocorrências')

@section('conteudo')
    <x-cabecalho-pagina titulo="Ocorrências" descricao="Registo de ocorrências comportamentais e académicas.">
        <x-botao-link href="{{ route('ocorrencias.create') }}">+ Nova ocorrência</x-botao-link>
    </x-cabecalho-pagina>

    <x-alerta />

    <form method="GET" action="{{ route('ocorrencias.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <div class="min-w-[200px]">
            <label for="aluno_id" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Aluno</label>
            <select id="aluno_id" name="aluno_id" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                <option value="">Todos</option>
                @foreach ($alunosDisponiveis as $aluno)
                    <option value="{{ $aluno->id }}" @selected((string) request('aluno_id') === (string) $aluno->id)>{{ $aluno->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[160px]">
            <label for="tipo" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Tipo</label>
            <select id="tipo" name="tipo" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                <option value="">Todos</option>
                @foreach (['comportamento' => 'Comportamento', 'academico' => 'Académico', 'disciplinar' => 'Disciplinar', 'elogio' => 'Elogio'] as $valor => $rotulo)
                    <option value="{{ $valor }}" @selected(request('tipo') === $valor)>{{ $rotulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[140px]">
            <label for="gravidade" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Gravidade</label>
            <select id="gravidade" name="gravidade" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                <option value="">Todas</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @selected((string) request('gravidade') === (string) $i)>{{ $i }} estrela{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
        </div>

        <div>
            <label for="de" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">De</label>
            <input id="de" name="de" type="date" value="{{ request('de') }}" class="rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
        </div>

        <div>
            <label for="ate" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Até</label>
            <input id="ate" name="ate" type="date" value="{{ request('ate') }}" class="rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
        </div>

        <x-botao tipo="submit" variante="secundario">Filtrar</x-botao>
    </form>

    @if ($ocorrencias->isEmpty())
        <x-estado-vazio icone="⭐" titulo="Nenhuma ocorrência encontrada" descricao="Ajuste os filtros ou registe uma nova ocorrência." />
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-azul-claro-100 text-xs uppercase text-marinho-700 dark:bg-white/5 dark:text-azul-claro-200">
                    <tr>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Aluno</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Gravidade</th>
                        <th class="px-4 py-3">Autor</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($ocorrencias as $ocorrencia)
                        <tr>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $ocorrencia->data->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium text-marinho-900 dark:text-white">{{ $ocorrencia->aluno->nome }}</td>
                            <td class="px-4 py-3 capitalize text-marinho-700 dark:text-azul-claro-200">{{ $ocorrencia->tipo }}</td>
                            <td class="px-4 py-3"><x-estrelas :valor="$ocorrencia->gravidade" /></td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $ocorrencia->autor->name }}</td>
                            <td class="px-4 py-3 text-right">
                                @can('update', $ocorrencia)
                                    <a href="{{ route('ocorrencias.edit', $ocorrencia) }}" class="text-sm font-medium text-azul-500 hover:underline">Editar</a>
                                @endcan
                                @can('delete', $ocorrencia)
                                    <form method="POST" action="{{ route('ocorrencias.destroy', $ocorrencia) }}" class="inline" onsubmit="return confirm('Eliminar esta ocorrência?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-sm font-medium text-red-600 hover:underline dark:text-red-400">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $ocorrencias->links() }}</div>
    @endif
@endsection
