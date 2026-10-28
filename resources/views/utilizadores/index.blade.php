@extends('layouts.app')

@section('titulo', 'Utilizadores')

@section('conteudo')
    <x-cabecalho-pagina titulo="Utilizadores" descricao="Contas de direção e professores.">
        <x-botao-link href="{{ route('utilizadores.create') }}">+ Novo utilizador</x-botao-link>
    </x-cabecalho-pagina>

    <x-alerta />

    @if ($utilizadores->isEmpty())
        <x-estado-vazio icone="👥" titulo="Ainda não existem utilizadores" />
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-azul-claro-100 text-xs uppercase text-marinho-700 dark:bg-white/5 dark:text-azul-claro-200">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Perfil</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($utilizadores as $utilizador)
                        <tr>
                            <td class="px-4 py-3 font-medium text-marinho-900 dark:text-white">{{ $utilizador->name }}</td>
                            <td class="px-4 py-3 text-marinho-700 dark:text-azul-claro-200">{{ $utilizador->email }}</td>
                            <td class="px-4 py-3 capitalize text-marinho-700 dark:text-azul-claro-200">{{ $utilizador->perfil }}</td>
                            <td class="px-4 py-3">
                                @if ($utilizador->ativo)
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-300">Ativo</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">Inativo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('utilizadores.edit', $utilizador) }}" class="text-sm font-medium text-azul-500 hover:underline">Editar</a>
                                @if (! $utilizador->is(auth()->user()))
                                    <form method="POST" action="{{ route('utilizadores.destroy', $utilizador) }}" class="inline" onsubmit="return confirm('Eliminar este utilizador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-sm font-medium text-red-600 hover:underline dark:text-red-400">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $utilizadores->links() }}</div>
    @endif
@endsection
