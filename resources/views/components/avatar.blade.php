@props(['user', 'tamanho' => 'h-8 w-8', 'texto' => 'text-sm'])

@if ($user->foto)
    <img src="{{ $user->fotoUrl() }}" alt="Foto de {{ $user->name }}" class="{{ $tamanho }} rounded-full object-cover">
@else
    <span class="flex {{ $tamanho }} {{ $texto }} items-center justify-center rounded-full bg-marinho-900 font-semibold text-white dark:bg-azul-500">
        {{ Str::upper(Str::substr($user->name, 0, 1)) }}
    </span>
@endif
