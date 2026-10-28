@props(['titulo', 'descricao' => null])

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-semibold text-marinho-900 dark:text-white">{{ $titulo }}</h1>
        @if ($descricao)
            <p class="mt-1 text-sm text-marinho-600 dark:text-azul-claro-200">{{ $descricao }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex items-center gap-2">{{ $slot }}</div>
    @endif
</div>
