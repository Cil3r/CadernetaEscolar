@extends('layouts.app')

@section('titulo', 'Editar falta')

@section('conteudo')
    <x-cabecalho-pagina titulo="Editar falta" :descricao="$falta->aluno->nome" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('faltas.update', $falta) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-campo name="data" label="Data" type="date" required :value="$falta->data->format('Y-m-d')" />

            <label class="flex items-center gap-2 text-sm text-marinho-700 dark:text-azul-claro-200">
                <input type="hidden" name="justificada" value="0">
                <input type="checkbox" name="justificada" value="1" class="rounded border-marinho-700/30 text-azul-500 focus:ring-azul-500/40" @checked(old('justificada', $falta->justificada))>
                Falta justificada
            </label>

            <x-campo name="motivo" label="Motivo (se justificada)" :value="old('motivo', $falta->motivo)" />

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('faltas.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
