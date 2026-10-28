@extends('layouts.app')

@section('titulo', 'Editar ocorrência')

@section('conteudo')
    <x-cabecalho-pagina titulo="Editar ocorrência" :descricao="$ocorrencia->aluno->nome" />

    <x-alerta />

    <x-cartao class="max-w-2xl">
        <form method="POST" action="{{ route('ocorrencias.update', $ocorrencia) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <x-campo name="data" label="Data" type="date" required :value="$ocorrencia->data->format('Y-m-d')" />

                <x-campo name="tipo" label="Tipo" type="select" required :value="$ocorrencia->tipo">
                    @foreach (['comportamento' => 'Comportamento', 'academico' => 'Académico', 'disciplinar' => 'Disciplinar', 'elogio' => 'Elogio'] as $valor => $rotulo)
                        <option value="{{ $valor }}" @selected(old('tipo', $ocorrencia->tipo) === $valor)>{{ $rotulo }}</option>
                    @endforeach
                </x-campo>
            </div>

            <x-estrelas-input :valor="$ocorrencia->gravidade" />

            <x-campo name="descricao" label="Descrição (o que aconteceu ou o que o aluno fez)" type="textarea" rows="4" required :value="$ocorrencia->descricao" />

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('ocorrencias.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
