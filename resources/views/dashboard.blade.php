@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')
    <x-cabecalho-pagina titulo="Bem-vindo, {{ $user->name }}" descricao="Visão geral dos últimos 30 dias · {{ now()->translatedFormat('d/m/Y') }}" />

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Turmas</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totalTurmas }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Alunos ativos</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totalAlunos }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Ocorrências (30 dias)</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totalOcorrencias }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Faltas (30 dias)</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totalFaltas }}</p>
        </x-cartao>
    </div>

    <div class="mb-6 grid gap-6 lg:grid-cols-2">
        <x-cartao titulo="Ocorrências por gravidade">
            <canvas id="grafico-ocorrencias" height="220"></canvas>
        </x-cartao>
        <x-cartao titulo="Faltas: justificadas vs injustificadas">
            <canvas id="grafico-faltas" height="220"></canvas>
        </x-cartao>
    </div>

    <x-cartao titulo="Alunos em alerta">
        @if ($alunosEmAlerta->isEmpty())
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Nenhum aluno excede os limiares configurados. 🎉</p>
        @else
            <ul class="divide-y divide-black/5 dark:divide-white/10">
                @foreach ($alunosEmAlerta as $aluno)
                    <li class="flex items-center justify-between py-2 text-sm">
                        <div>
                            <a href="{{ route('alunos.caderneta', $aluno) }}" class="font-medium text-marinho-900 hover:underline dark:text-white">{{ $aluno->nome }}</a>
                            <span class="text-marinho-600 dark:text-azul-claro-200">· {{ $aluno->turma?->nome ?? 'Sem turma' }}</span>
                        </div>
                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">Em alerta</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-cartao>

    <script type="application/json" id="dados-dashboard">{!! json_encode($dadosGraficos) !!}</script>
@endsection

@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush
