@extends('layouts.app')

@section('titulo', 'Nova turma')

@section('conteudo')
    <x-cabecalho-pagina titulo="Nova turma" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('turmas.store') }}" class="space-y-5">
            @csrf
            @include('turmas._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('turmas.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar turma</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
