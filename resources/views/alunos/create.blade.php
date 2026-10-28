@extends('layouts.app')

@section('titulo', 'Novo aluno')

@section('conteudo')
    <x-cabecalho-pagina titulo="Novo aluno" />

    <x-alerta />

    <x-cartao class="max-w-3xl">
        <form method="POST" action="{{ route('alunos.store') }}" class="space-y-5">
            @csrf
            @include('alunos._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('alunos.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar aluno</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
