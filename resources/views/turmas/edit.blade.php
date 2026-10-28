@extends('layouts.app')

@section('titulo', 'Editar turma')

@section('conteudo')
    <x-cabecalho-pagina titulo="Editar turma" :descricao="$turma->nome" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('turmas.update', $turma) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('turmas._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('turmas.show', $turma) }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
