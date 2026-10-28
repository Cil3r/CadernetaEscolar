@extends('layouts.app')

@section('titulo', 'Configurações')

@section('conteudo')
    <x-cabecalho-pagina titulo="Configurações" descricao="Limiares de alerta usados na caderneta e no dashboard." />

    <x-alerta />

    <x-cartao class="max-w-xl" titulo="Limiares de alerta">
        <form method="POST" action="{{ route('configuracoes.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-campo
                name="limite_faltas"
                label="Limite de faltas injustificadas"
                type="number"
                min="1"
                max="365"
                required
                :value="old('limite_faltas', $limiteFaltas)"
            />
            <p class="text-xs text-marinho-600 dark:text-azul-claro-200">A partir deste número de faltas injustificadas, o aluno passa a aparecer em alerta.</p>

            <x-campo
                name="limite_gravidade"
                label="Limiar de gravidade das ocorrências"
                type="number"
                min="1"
                max="5"
                required
                :value="old('limite_gravidade', $limiteGravidade)"
            />
            <p class="text-xs text-marinho-600 dark:text-azul-claro-200">Ocorrências com esta gravidade (em estrelas) ou superior colocam o aluno em alerta.</p>

            <div class="flex justify-end">
                <x-botao tipo="submit">Guardar configurações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
