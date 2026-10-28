@props(['variante' => 'primario'])

@php
    $estilos = [
        'primario' => 'bg-marinho-900 text-white hover:bg-marinho-800 dark:bg-azul-500 dark:hover:bg-azul-500/90',
        'secundario' => 'bg-white text-marinho-800 ring-1 ring-marinho-700/20 hover:bg-azul-claro-100 dark:bg-marinho-800 dark:text-white dark:ring-white/10 dark:hover:bg-white/10',
    ][$variante];
@endphp

<a {{ $attributes->except('variante') }} class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition {{ $estilos }}">
    {{ $slot }}
</a>
