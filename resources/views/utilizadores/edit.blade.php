@extends('layouts.app')

@section('titulo', 'Editar utilizador')

@section('conteudo')
    <x-cabecalho-pagina titulo="Editar utilizador" :descricao="$utilizador->name" />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('utilizadores.update', $utilizador) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            @include('utilizadores._campos')

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('utilizadores.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
