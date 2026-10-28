@props(['valor'])

<span class="inline-flex items-center gap-0.5 text-amber-500" aria-label="Gravidade {{ $valor }} de 5">
    @for ($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $valor ? 'opacity-100' : 'opacity-20' }}" aria-hidden="true">⭐</span>
    @endfor
</span>
