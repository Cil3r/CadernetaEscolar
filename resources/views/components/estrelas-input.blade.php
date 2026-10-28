@props(['name' => 'gravidade', 'valor' => null])

<div>
    <label class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Gravidade <span class="text-red-500">*</span></label>
    <div role="radiogroup" class="flex items-center gap-4">
        @for ($i = 1; $i <= 5; $i++)
            <label class="flex cursor-pointer flex-col items-center gap-1">
                <input type="radio" name="{{ $name }}" value="{{ $i }}" class="peer sr-only" @checked((int) old($name, $valor) === $i) required>
                <span class="text-2xl text-marinho-700/20 transition peer-checked:scale-110 peer-checked:text-amber-500 dark:text-white/20">⭐</span>
                <span class="text-xs text-marinho-600 dark:text-azul-claro-200">{{ $i }}</span>
            </label>
        @endfor
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
