<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Dashboard') · Caderneta Digital</title>

    <script>
        (function () {
            var tema = localStorage.getItem('caderneta-tema')
                ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'escuro' : 'claro');
            document.documentElement.classList.toggle('dark', tema === 'escuro');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-azul-claro-100 text-marinho-950 antialiased dark:bg-marinho-950 dark:text-white">
    @php
        $navegacao = [
            ['rota' => 'dashboard', 'label' => 'Dashboard', 'icone' => '🏠', 'perfis' => ['direcao', 'professor', 'super_admin']],
            ['rota' => 'turmas.index', 'label' => 'Turmas', 'icone' => '🏫', 'perfis' => ['direcao', 'professor', 'super_admin']],
            ['rota' => 'alunos.index', 'label' => 'Alunos', 'icone' => '🎓', 'perfis' => ['direcao', 'professor', 'super_admin']],
            ['rota' => 'ocorrencias.index', 'label' => 'Ocorrências', 'icone' => '⭐', 'perfis' => ['direcao', 'professor', 'super_admin']],
            ['rota' => 'faltas.index', 'label' => 'Faltas', 'icone' => '📅', 'perfis' => ['direcao', 'professor', 'super_admin']],
            ['rota' => 'utilizadores.index', 'label' => 'Utilizadores', 'icone' => '👥', 'perfis' => ['direcao', 'super_admin']],
            ['rota' => 'configuracoes.index', 'label' => 'Configurações', 'icone' => '⚙️', 'perfis' => ['direcao', 'super_admin']],
        ];

        $rotulosPerfil = ['super_admin' => 'Super Admin', 'direcao' => 'Direção', 'professor' => 'Professor'];
    @endphp

    <div data-sobreposicao-menu class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

    <div class="flex min-h-screen">
        <aside
            data-sidebar
            class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-marinho-950 text-white transition-transform duration-200 lg:static lg:translate-x-0"
        >
            <div class="flex h-16 items-center gap-2 px-5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-azul-500 font-bold">CD</div>
                <span class="text-lg font-semibold">Caderneta Digital</span>
            </div>

            <nav class="scrollbar-fina flex-1 space-y-1 overflow-y-auto px-3 py-4">
                @foreach ($navegacao as $item)
                    @continue(! in_array(auth()->user()->perfil, $item['perfis']))

                    @php $ativo = Route::is($item['rota']) || Route::is(str($item['rota'])->before('.').'.*'); @endphp

                    @if (Route::has($item['rota']))
                        <a
                            href="{{ route($item['rota']) }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $ativo ? 'bg-azul-500 text-white' : 'text-azul-claro-200 hover:bg-white/10' }}"
                        >
                            <span aria-hidden="true">{{ $item['icone'] }}</span>
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/30">
                            <span aria-hidden="true">{{ $item['icone'] }}</span>
                            {{ $item['label'] }}
                            <span class="ml-auto rounded-full bg-white/10 px-2 py-0.5 text-[10px] uppercase">Em breve</span>
                        </span>
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-white/10 px-5 py-4 text-xs text-azul-claro-200">
                &copy; {{ now()->year }} Caderneta Digital
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-0">
            <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-black/5 bg-white px-4 shadow-sm dark:border-white/10 dark:bg-marinho-900 sm:px-6">
                <button type="button" data-abrir-menu class="rounded-lg p-2 text-marinho-700 hover:bg-azul-claro-100 dark:text-white dark:hover:bg-white/10 lg:hidden" aria-label="Abrir menu">
                    ☰
                </button>

                <form class="hidden flex-1 max-w-md sm:block">
                    <label for="pesquisa-global" class="sr-only">Pesquisar</label>
                    <input
                        id="pesquisa-global"
                        type="search"
                        placeholder="Pesquisar alunos, turmas, ocorrências…"
                        class="w-full rounded-lg border border-marinho-700/15 bg-azul-claro-100 px-4 py-2 text-sm text-marinho-950 focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/30 dark:border-white/10 dark:bg-marinho-800 dark:text-white dark:placeholder:text-white/40"
                    >
                </form>

                <div class="ml-auto flex items-center gap-2">
                    <button
                        type="button"
                        data-alternar-tema
                        class="rounded-lg p-2 text-marinho-700 transition hover:bg-azul-claro-100 dark:text-white dark:hover:bg-white/10"
                        aria-label="Alternar modo claro/escuro"
                    >
                        🌓
                    </button>

                    <div class="relative">
                        <button
                            type="button"
                            data-dropdown-botao="notificacoes"
                            class="rounded-lg p-2 text-marinho-700 transition hover:bg-azul-claro-100 dark:text-white dark:hover:bg-white/10"
                            aria-label="Notificações"
                        >
                            🔔
                        </button>

                        <div data-dropdown-painel="notificacoes" class="absolute right-0 z-30 mt-2 hidden w-72 rounded-xl border border-black/5 bg-white p-4 text-sm shadow-xl dark:border-white/10 dark:bg-marinho-800">
                            <p class="font-medium text-marinho-900 dark:text-white">Notificações</p>
                            <p class="mt-2 text-marinho-700 dark:text-azul-claro-200">Sem notificações por agora.</p>
                        </div>
                    </div>

                    <div class="relative">
                        <button
                            type="button"
                            data-dropdown-botao="perfil"
                            class="flex items-center gap-2 rounded-lg px-2 py-1.5 transition hover:bg-azul-claro-100 dark:hover:bg-white/10"
                        >
                            <x-avatar :user="auth()->user()" />
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-medium text-marinho-900 dark:text-white">{{ auth()->user()->name }}</span>
                                <span class="block text-xs text-marinho-600 dark:text-azul-claro-200">{{ $rotulosPerfil[auth()->user()->perfil] ?? auth()->user()->perfil }}</span>
                            </span>
                        </button>

                        <div data-dropdown-painel="perfil" class="absolute right-0 z-30 mt-2 hidden w-48 rounded-xl border border-black/5 bg-white p-2 text-sm shadow-xl dark:border-white/10 dark:bg-marinho-800">
                            @if (Route::has('perfil.edit'))
                                <a href="{{ route('perfil.edit') }}" class="block rounded-lg px-3 py-2 text-left text-marinho-800 transition hover:bg-azul-claro-100 dark:text-azul-claro-200 dark:hover:bg-white/10">
                                    O meu perfil
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6">
                @yield('conteudo')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
