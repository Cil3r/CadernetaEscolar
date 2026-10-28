@props(['icone' => '📭', 'titulo', 'descricao' => null])

<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-marinho-700/20 bg-white px-6 py-14 text-center dark:border-white/10 dark:bg-marinho-900">
    <span class="mb-3 text-4xl" aria-hidden="true">{{ $icone }}</span>
    <p class="text-sm font-medium text-marinho-900 dark:text-white">{{ $titulo }}</p>
    @if ($descricao)
        <p class="mt-1 max-w-sm text-sm text-marinho-600 dark:text-azul-claro-200">{{ $descricao }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
