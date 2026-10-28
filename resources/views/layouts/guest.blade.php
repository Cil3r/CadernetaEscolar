<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Caderneta Digital') · Caderneta Digital</title>

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
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <div class="mb-8 flex flex-col items-center gap-2">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-marinho-900 text-2xl font-bold text-white shadow-lg dark:bg-azul-500">
                CD
            </div>
            <h1 class="text-xl font-semibold text-marinho-900 dark:text-white">Caderneta Digital</h1>
        </div>

        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10">
            @yield('conteudo')
        </div>

        <button
            type="button"
            data-alternar-tema
            class="mt-6 inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-marinho-700 transition hover:bg-white/60 dark:text-azul-claro-200 dark:hover:bg-white/10"
        >
            <span aria-hidden="true">🌓</span> Alternar modo claro/escuro
        </button>
    </div>
</body>
</html>
