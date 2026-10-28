@extends('layouts.app')

@section('titulo', 'Nova ocorrência')

@section('conteudo')
    <x-cabecalho-pagina titulo="Nova ocorrência" />

    <x-alerta />

    <x-cartao class="max-w-2xl">
        <form method="POST" action="{{ route('ocorrencias.store') }}" class="space-y-5">
            @csrf

            <x-campo name="aluno_id" label="Aluno" type="select" required>
                <option value="">Escolha o aluno</option>
                @foreach ($alunosDisponiveis as $aluno)
                    <option value="{{ $aluno->id }}" @selected((int) old('aluno_id', $alunoSelecionado) === $aluno->id)>{{ $aluno->nome }}</option>
                @endforeach
            </x-campo>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-campo name="data" label="Data" type="date" required :value="now()->format('Y-m-d')" />

                <x-campo name="tipo" label="Tipo" type="select" required>
                    @foreach (['comportamento' => 'Comportamento', 'academico' => 'Académico', 'disciplinar' => 'Disciplinar', 'elogio' => 'Elogio'] as $valor => $rotulo)
                        <option value="{{ $valor }}" @selected(old('tipo') === $valor)>{{ $rotulo }}</option>
                    @endforeach
                </x-campo>
            </div>

            <x-estrelas-input />

            <x-campo name="descricao" label="Descrição" type="textarea" rows="4" required />

            <div class="flex justify-end gap-2">
                <x-botao-link variante="secundario" href="{{ route('ocorrencias.index') }}">Cancelar</x-botao-link>
                <x-botao tipo="submit">Registar ocorrência</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
