@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')
    @php
        $mesmoDia = $de->isSameDay($ate);
        $descricaoPeriodo = $mesmoDia && $de->isToday()
            ? 'Hoje · '.$de->translatedFormat('d/m/Y')
            : 'De '.$de->format('d/m/Y').' até '.$ate->format('d/m/Y');

        $presets = [
            'Hoje' => ['de' => now()->format('Y-m-d'), 'ate' => now()->format('Y-m-d')],
            '7 dias' => ['de' => now()->subDays(6)->format('Y-m-d'), 'ate' => now()->format('Y-m-d')],
            '30 dias' => ['de' => now()->subDays(29)->format('Y-m-d'), 'ate' => now()->format('Y-m-d')],
            'Este mês' => ['de' => now()->startOfMonth()->format('Y-m-d'), 'ate' => now()->format('Y-m-d')],
        ];
    @endphp

    <x-cabecalho-pagina titulo="Bem-vindo, {{ $user->name }}" :descricao="$descricaoPeriodo" />

    <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <div class="flex flex-wrap gap-2">
            @foreach ($presets as $rotulo => $intervalo)
                <a
                    href="{{ route('dashboard', $intervalo) }}"
                    class="rounded-full px-3 py-1.5 text-xs font-medium transition {{ request('de') === $intervalo['de'] && request('ate') === $intervalo['ate'] ? 'bg-marinho-900 text-white dark:bg-azul-500' : 'bg-azul-claro-100 text-marinho-700 hover:bg-azul-claro-200 dark:bg-white/10 dark:text-azul-claro-200 dark:hover:bg-white/20' }}"
                >
                    {{ $rotulo }}
                </a>
            @endforeach
        </div>

        <div>
            <label for="de" class="mb-1 block text-xs font-medium text-marinho-800 dark:text-azul-claro-200">De</label>
            <input id="de" name="de" type="date" value="{{ $de->format('Y-m-d') }}" class="rounded-lg border border-marinho-700/20 bg-white px-3 py-1.5 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
        </div>

        <div>
            <label for="ate" class="mb-1 block text-xs font-medium text-marinho-800 dark:text-azul-claro-200">Até</label>
            <input id="ate" name="ate" type="date" value="{{ $ate->format('Y-m-d') }}" class="rounded-lg border border-marinho-700/20 bg-white px-3 py-1.5 text-sm text-marinho-950 dark:border-white/10 dark:bg-marinho-800 dark:text-white">
        </div>

        <x-botao tipo="submit" variante="secundario">Filtrar</x-botao>
    </form>

    <x-alerta />

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
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Ocorrências no período</p>
            <p class="mt-1 text-2xl font-semibold text-marinho-900 dark:text-white">{{ $totalOcorrencias }}</p>
        </x-cartao>
        <x-cartao>
            <p class="text-sm text-marinho-600 dark:text-azul-claro-200">Faltas no período</p>
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
