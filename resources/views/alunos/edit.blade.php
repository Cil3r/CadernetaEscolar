@extends('layouts.app')

@section('titulo', 'Editar aluno')

@section('conteudo')
    <x-cabecalho-pagina titulo="Editar aluno" :descricao="$aluno->nome" />

    <x-alerta />

    <x-cartao class="max-w-3xl">
        <form method="POST" action="{{ route('alunos.update', $aluno) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('alunos._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('alunos.show', $aluno) }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
