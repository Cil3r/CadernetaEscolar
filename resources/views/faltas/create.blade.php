@extends('layouts.app')

@section('titulo', 'Nova falta')

@section('conteudo')
    <x-cabecalho-pagina titulo="Nova falta" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('faltas.store') }}" class="space-y-5">
            @csrf

            <x-campo name="aluno_id" label="Aluno" type="select" required>
                <option value="">Escolha o aluno</option>
                @foreach ($alunosDisponiveis as $aluno)
                    <option value="{{ $aluno->id }}" @selected((int) old('aluno_id', $alunoSelecionado) === $aluno->id)>{{ $aluno->nome }}</option>
                @endforeach
            </x-campo>

            <x-campo name="data" label="Data" type="date" required :value="now()->format('Y-m-d')" />

            <label class="flex items-center gap-2 text-sm text-marinho-700 dark:text-azul-claro-200">
                <input type="hidden" name="justificada" value="0">
                <input type="checkbox" name="justificada" value="1" class="rounded border-marinho-700/30 text-azul-500 focus:ring-azul-500/40" @checked(old('justificada'))>
                Falta justificada
            </label>

            <x-campo name="motivo" label="Motivo (se justificada)" :value="old('motivo')" />

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('faltas.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Registar falta</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
