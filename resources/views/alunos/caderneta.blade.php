@extends('layouts.app')

@section('titulo', 'Caderneta de '.$aluno->nome)

@section('conteudo')
    <x-cabecalho-pagina :titulo="'Caderneta · '.$aluno->nome" descricao="{{ $aluno->numero_processo }} · {{ $aluno->turma?->nome ?? 'Sem turma' }}">
        <x-botao-link variante="secundario" href="{{ route('alunos.show', $aluno) }}">Voltar ao aluno</x-botao-link>
    </x-cabecalho-pagina>

    @if ($alertas['faltas'])
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            ⚠️ Este aluno atingiu ou superou o limite de {{ $limites['faltas'] }} faltas injustificadas configurado.
        </div>
    @endif

    @if ($alertas['gravidade'])
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            ⚠️ Este aluno tem ocorrências com gravidade igual ou superior a {{ $limites['gravidade'] }} estrelas.
        </div>
    @endif

    <div class="mb-6 grid gap-4 sm:grid-cols-4">
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Ocorrências</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $resumo['total_ocorrencias'] }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Total de faltas</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $resumo['total_faltas'] }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Faltas justificadas</p>
            <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ $resumo['faltas_justificadas'] }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Faltas injustificadas</p>
            <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">{{ $resumo['faltas_injustificadas'] }}</p>
        </x-cartao>
    </div>

    <x-cartao titulo="Histórico">
        @if ($eventos->isEmpty())
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Ainda não há registos para este aluno.</p>
        @else
            <ul class="divide-y divide-black/5 dark:divide-white/10">
                @foreach ($eventos as $evento)
                    <li class="flex flex-col gap-1 py-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            @if ($evento['tipo'] === 'ocorrencia')
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-azul-claro-100 px-2 py-0.5 text-xs font-medium capitalize text-marinho-900 dark:bg-white/10 dark:text-azul-claro-200">{{ $evento['modelo']->tipo }}</span>
                                    <x-estrelas :valor="$evento['modelo']->gravidade" />
                                </div>
                                <p class="mt-1 text-sm text-marinho-900 dark:text-white">{{ $evento['modelo']->descricao }}</p>
                            @else
                                <div class="flex items-center gap-2">
                                    @if ($evento['modelo']->justificada)
                                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-300">Falta justificada</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">Falta injustificada</span>
                                    @endif
                                </div>
                                @if ($evento['modelo']->motivo)
                                    <p class="mt-1 text-sm text-marinho-900 dark:text-white">{{ $evento['modelo']->motivo }}</p>
                                @endif
                            @endif
                            <p class="mt-1 text-xs text-marinho-600 dark:text-azul-claro-200">Registado por {{ $evento['modelo']->autor->name }}</p>
                        </div>
                        <span class="shrink-0 text-sm text-marinho-600 dark:text-azul-claro-200">{{ $evento['data']->format('d/m/Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-cartao>
@endsection
