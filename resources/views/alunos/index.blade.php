@extends('layouts.app')

@section('titulo', 'Alunos')

@section('conteudo')
    <x-cabecalho-pagina titulo="Alunos" descricao="Lista de alunos matriculados.">
        <x-botao-link href="{{ route('alunos.create') }}">+ Novo aluno</x-botao-link>
    </x-cabecalho-pagina>

    <x-alerta />

    <form method="GET" action="{{ route('alunos.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label for="pesquisa" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Pesquisar</label>
            <input id="pesquisa" name="pesquisa" type="search" value="{{ request('pesquisa') }}" placeholder="Nome ou número de processo" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
        </div>
        <div class="min-w-[180px]">
            <label for="turma_id" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Turma</label>
            <select id="turma_id" name="turma_id" class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
                <option value="">Todas</option>
                @foreach ($turmasDisponiveis as $turma)
                    <option value="{{ $turma->id }}" @selected((string) request('turma_id') === (string) $turma->id)>{{ $turma->nome }}</option>
                @endforeach
            </select>
        </div>
        <x-botao tipo="submit" variante="secundario">Filtrar</x-botao>
    </form>

    @if ($alunos->isEmpty())
        <x-estado-vazio icone="🎓" titulo="Nenhum aluno encontrado" descricao="Ajuste os filtros ou crie um novo aluno." />
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-azul-claro-100 text-xs uppercase text-marinho-700 dark:bg-white/5 dark:text-azul-claro-200">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Nº processo</th>
                        <th class="px-4 py-3">Turma</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($alunos as $aluno)
                        <tr>
                            <td class="px-4 py-3 font-medium text-marinho-900 dark:text-white">
                                <a href="{{ route('alunos.caderneta', $aluno) }}" class="hover:underline" title="Ver caderneta">{{ $aluno->nome }}</a>
                            </td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $aluno->numero_processo }}</td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $aluno->turma?->nome ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($aluno->ativo)
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-300">Ativo</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">Inativo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('alunos.show', $aluno) }}" class="text-sm font-medium text-azul-500 hover:underline">Dados</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $alunos->links() }}</div>
    @endif
@endsection
