@extends('layouts.app')

@section('titulo', 'Turmas')

@section('conteudo')
    <x-cabecalho-pagina titulo="Turmas" descricao="Gestão das turmas da instituição.">
        @can('create', App\Models\Turma::class)
            <x-botao-link href="{{ route('turmas.create') }}">+ Nova turma</x-botao-link>
        @endcan
    </x-cabecalho-pagina>

    <x-alerta />

    @if ($turmas->isEmpty())
        <x-estado-vazio
            icone="🏫"
            titulo="Ainda não existem turmas"
            :descricao="auth()->user()->isGestor() ? 'Comece por criar a primeira turma da instituição.' : 'Ainda não lhe foram atribuídas turmas.'"
        >
            @can('create', App\Models\Turma::class)
                <a href="{{ route('turmas.create') }}" class="text-sm font-medium text-azul-500 hover:underline">Criar turma</a>
            @endcan
        </x-estado-vazio>
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-azul-claro-100 text-xs uppercase text-marinho-700 dark:bg-white/5 dark:text-azul-claro-200">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Classe</th>
                        <th class="px-4 py-3">Ano letivo</th>
                        <th class="px-4 py-3">Período</th>
                        <th class="px-4 py-3">Alunos</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($turmas as $turma)
                        <tr>
                            <td class="px-4 py-3 font-medium text-marinho-900 dark:text-white">{{ $turma->nome }}</td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $turma->classe }}</td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $turma->ano_letivo }}</td>
                            <td class="px-4 py-3 capitalize text-marinho-700 dark:text-azul-claro-200">{{ $turma->periodo }}</td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $turma->alunos_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('turmas.show', $turma) }}" class="text-sm font-medium text-azul-500 hover:underline">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $turmas->links() }}</div>
    @endif
@endsection
