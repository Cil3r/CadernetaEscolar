@extends('layouts.app')

@section('titulo', $aluno->nome)

@section('conteudo')
    <x-cabecalho-pagina :titulo="$aluno->nome" descricao="Nº processo {{ $aluno->numero_processo }} · {{ $aluno->turma?->nome ?? 'Sem turma' }}">
        @if (Route::has('alunos.caderneta'))
            <x-botao-link href="{{ route('alunos.caderneta', $aluno) }}">Ver caderneta</x-botao-link>
        @endif
        @can('update', $aluno)
            <x-botao-link variante="secundario" href="{{ route('alunos.edit', $aluno) }}">Editar</x-botao-link>
        @endcan
        @can('delete', $aluno)
            <form method="POST" action="{{ route('alunos.destroy', $aluno) }}" onsubmit="return confirm('Eliminar este aluno?');">
                @csrf
                @method('DELETE')
                <x-botao variante="perigo" tipo="submit">Eliminar</x-botao>
            </form>
        @endcan
    </x-cabecalho-pagina>

    <x-alerta />

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <x-cartao titulo="Dados pessoais">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Género</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->genero === 'F' ? 'Feminino' : ($aluno->genero === 'M' ? 'Masculino' : '—') }}</dd></div>
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Nascimento</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Estado</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->ativo ? 'Ativo' : 'Inativo' }}</dd></div>
            </dl>
        </x-cartao>

        <x-cartao titulo="Encarregado">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Nome</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->encarregado_nome ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Contacto</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->encarregado_contacto ?? '—' }}</dd></div>
            </dl>
        </x-cartao>

        <x-cartao titulo="Turma">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Nome</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->turma?->nome ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-marinho-600 dark:text-azul-claro-200">Classe</dt><dd class="text-marinho-900 dark:text-white">{{ $aluno->turma?->classe ?? '—' }}</dd></div>
            </dl>
        </x-cartao>
    </div>
@endsection
