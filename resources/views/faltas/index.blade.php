@extends('layouts.app')

@section('titulo', 'Faltas')

@section('conteudo')
    <x-cabecalho-pagina titulo="Faltas" descricao="Registo de faltas justificadas e injustificadas.">
        <x-botao-link href="{{ route('faltas.create') }}">+ Nova falta</x-botao-link>
    </x-cabecalho-pagina>

    <x-alerta />

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Total de faltas</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totais['total'] }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Justificadas</p>
            <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ $totais['justificadas'] }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Injustificadas</p>
            <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">{{ $totais['injustificadas'] }}</p>
        </x-cartao>
    </div>

    <form method="GET" action="{{ route('faltas.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
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
            <label for="estado" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Estado</label>
            <select id="estado" name="estado" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                <option value="">Todas</option>
                <option value="justificada" @selected(request('estado') === 'justificada')>Justificadas</option>
                <option value="injustificada" @selected(request('estado') === 'injustificada')>Injustificadas</option>
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

    @if ($faltas->isEmpty())
        <x-estado-vazio icone="📅" titulo="Nenhuma falta encontrada" descricao="Ajuste os filtros ou registe uma nova falta." />
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-azul-claro-100 text-xs uppercase text-marinho-700 dark:bg-white/5 dark:text-azul-claro-200">
                    <tr>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Aluno</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Motivo</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($faltas as $falta)
                        <tr>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $falta->data->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium text-marinho-900 dark:text-white">{{ $falta->aluno->nome }}</td>
                            <td class="px-4 py-3">
                                @if ($falta->justificada)
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-300">Justificada</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">Injustificada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $falta->motivo ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                @can('update', $falta)
                                    <a href="{{ route('faltas.edit', $falta) }}" class="text-sm font-medium text-azul-500 hover:underline">Editar</a>
                                @endcan
                                @can('delete', $falta)
                                    <form method="POST" action="{{ route('faltas.destroy', $falta) }}" class="inline" onsubmit="return confirm('Eliminar esta falta?');">
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

        <div class="mt-4">{{ $faltas->links() }}</div>
    @endif
@endsection
