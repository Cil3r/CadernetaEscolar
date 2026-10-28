@extends('layouts.guest')

@section('titulo', 'Entrar')

@section('conteudo')
    <h2 class="mb-1 text-lg font-semibold text-marinho-900 dark:text-white">Bem-vindo de volta</h2>
    <p class="mb-6 text-sm text-marinho-700 dark:text-azul-claro-200">Entre com as suas credenciais para aceder à caderneta.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="direcao@caderneta.ao"
                class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
            >
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Senha</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
            >
        </div>

        <label class="flex items-center gap-2 text-sm text-marinho-700 dark:text-azul-claro-200">
            <input type="checkbox" name="lembrar" class="rounded border-marinho-700/30 text-azul-500 focus:ring-azul-500/40">
            Lembrar-me
        </label>

        <button
            type="submit"
            class="w-full rounded-lg bg-marinho-900 px-4 py-2.5 font-medium text-white shadow-md transition hover:bg-marinho-800 focus:outline-none focus:ring-2 focus:ring-azul-500/50 dark:bg-azul-500 dark:hover:bg-azul-500/90"
        >
            Entrar
        </button>
    </form>
@endsection
