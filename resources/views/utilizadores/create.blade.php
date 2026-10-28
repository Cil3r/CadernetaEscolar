@extends('layouts.app')

@section('titulo', 'Novo utilizador')

@section('conteudo')
    <x-cabecalho-pagina titulo="Novo utilizador" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('utilizadores.store') }}" class="space-y-5">
            @csrf
            @include('utilizadores._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('utilizadores.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar utilizador</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
