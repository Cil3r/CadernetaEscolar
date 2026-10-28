@props(['titulo' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 dark:bg-marinho-900 dark:ring-white/10']) }}>
    @if ($titulo)
        <h2 class="mb-4 text-lg font-semibold text-marinho-900 dark:text-white">{{ $titulo }}</h2>
    @endif

    {{ $slot }}
</div>
