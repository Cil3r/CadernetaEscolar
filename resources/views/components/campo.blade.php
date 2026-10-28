@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>

    @if ($type === 'select')
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->except(['name', 'label', 'type', 'value', 'required']) }}
            class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
        >
            {{ $slot }}
        </select>
    @elseif ($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->except(['name', 'label', 'type', 'value', 'required']) }}
            class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            id="{{ $name }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->except(['name', 'label', 'type', 'value', 'required']) }}
            class="w-full rounded-lg border border-marinho-700/20 bg-white px-3 py-2 text-marinho-950 shadow-sm transition focus:border-azul-500 focus:outline-none focus:ring-2 focus:ring-azul-500/40 dark:border-white/10 dark:bg-marinho-800 dark:text-white"
        >
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
