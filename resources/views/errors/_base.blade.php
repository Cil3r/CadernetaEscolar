<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? 'Erro' }} · Caderneta Digital</title>

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
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10 text-center">
        <span class="mb-4 text-5xl" aria-hidden="true">{{ $icone ?? '⚠️' }}</span>
        <h1 class="text-2xl font-semibold text-marinho-900 dark:text-white">{{ $titulo ?? 'Ocorreu um erro' }}</h1>
        <p class="mt-2 max-w-md text-sm text-marinho-600 dark:text-azul-claro-200">{{ $mensagem ?? 'Tente novamente dentro de momentos.' }}</p>

        <a href="{{ url('/') }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-lg bg-marinho-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-marinho-800 dark:bg-azul-500 dark:hover:bg-azul-500/90">
            Voltar ao início
        </a>
    </div>
</body>
</html>
