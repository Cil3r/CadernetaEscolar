@props(['variante' => 'primario', 'tipo' => 'button'])

@php
    $estilos = [
        'primario' => 'bg-marinho-900 text-white hover:bg-marinho-800 dark:bg-azul-500 dark:hover:bg-azul-500/90 focus:ring-azul-500/50',
        'secundario' => 'bg-white text-marinho-800 ring-1 ring-marinho-700/20 hover:bg-azul-claro-100 dark:bg-marinho-800 dark:text-white dark:ring-white/10 dark:hover:bg-white/10 focus:ring-azul-500/30',
        'perigo' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500/50',
    ][$variante];
@endphp

<button type="{{ $tipo }}" {{ $attributes->except(['variante', 'tipo']) }} class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition focus:outline-none focus:ring-2 {{ $estilos }}">
    {{ $slot }}
</button>
