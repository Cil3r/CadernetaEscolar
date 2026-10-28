@extends('layouts.app')

@section('titulo', 'O meu perfil')

@section('conteudo')
    <x-cabecalho-pagina titulo="O meu perfil" descricao="Atualize os seus dados, foto e senha." />

    <x-alerta />

    <x-cartao class="max-w-xl">
        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4">
                <x-avatar :user="$user" tamanho="h-16 w-16" texto="text-xl" />
                <div class="flex-1">
                    <label for="foto" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Foto de perfil</label>
                    <input id="foto" name="foto" type="file" accept="image/*" class="block w-full text-sm text-marinho-700 dark:text-azul-claro-200">
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-campo name="name" label="Nome completo" required :value="$user->name" />

            <x-campo name="email" label="Email" type="email" :value="$user->email" disabled />
            <p class="text-xs text-marinho-600 dark:text-azul-claro-200">O email não pode ser alterado aqui. Contacte a direção se precisar de o atualizar.</p>

            <x-campo name="password" label="Nova senha (opcional)" type="password" />
            <x-campo name="password_confirmation" label="Confirmar nova senha" type="password" />

            <div class="flex justify-end">
                <x-botao tipo="submit">Guardar alterações</x-botao>
            </div>
        </form>
    </x-cartao>
@endsection
