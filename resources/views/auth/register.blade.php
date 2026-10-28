@extends('layouts.guest')

@section('titulo', 'Criar conta')

@section('conteudo')
    <h2 class="mb-1 text-lg font-semibold text-marinho-900 dark:text-white">Criar conta</h2>
    <p class="mb-6 text-sm text-marinho-700 dark:text-azul-claro-200">Registe-se como professor. A sua conta fica pendente de ativação pela direção.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Nome completo</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
            >
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
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
                autocomplete="new-password"
                class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Confirmar senha</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-marinho-900 px-4 py-2.5 font-medium text-white shadow-md transition hover:bg-marinho-800 focus:outline-none focus:ring-2 focus:ring-azul-500/50 dark:bg-azul-500 dark:hover:bg-azul-500/90"
        >
            Criar conta
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-marinho-700 dark:text-azul-claro-200">
        Já tem conta? <a href="{{ route('login') }}" class="font-medium text-azul-500 hover:underline">Entrar</a>
    </p>
@endsection
