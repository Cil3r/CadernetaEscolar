@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
        <h1 class="text-xl font-semibold text-marinho-900 dark:text-white">
            Bem-vindo, {{ $user->name }}
        </h1>
        <p class="mt-1 text-sm text-marinho-700 dark:text-azul-claro-200">
            Está autenticado como
            <span class="inline-flex items-center rounded-full bg-azul-claro-100 px-2.5 py-0.5 text-xs font-semibold text-marinho-900 dark:bg-white/10 dark:text-azul-claro-200">
                {{ $user->perfil === 'direcao' ? 'Direção' : 'Professor' }}
            </span>
            · {{ now()->translatedFormat('d/m/Y') }}
        </p>

        <p class="mt-6 text-sm text-marinho-600 dark:text-azul-claro-200">
            O painel com indicadores e gráficos será disponibilizado no módulo de dashboard.
        </p>
    </div>
@endsection
