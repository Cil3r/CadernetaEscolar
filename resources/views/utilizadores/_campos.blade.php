@php $utilizador = $utilizador ?? null; @endphp

@if ($utilizador)
    <div class="flex items-center gap-4">
        <x-avatar :user="$utilizador" tamanho="h-14 w-14" texto="text-lg" />
        <div class="flex-1">
            <label for="foto" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Foto de perfil</label>
            <input id="foto" name="foto" type="file" accept="image/*" class="block w-full text-sm text-marinho-700 dark:text-azul-claro-200">
        </div>
    </div>
@else
    <div>
        <label for="foto" class="mb-1 block text-sm font-medium text-marinho-800 dark:text-azul-claro-200">Foto de perfil (opcional)</label>
        <input id="foto" name="foto" type="file" accept="image/*" class="block w-full text-sm text-marinho-700 dark:text-azul-claro-200">
    </div>
@endif
@error('foto')
    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<x-campo name="name" label="Nome completo" required :value="$utilizador?->name" />

<x-campo name="email" label="Email" type="email" required :value="$utilizador?->email" />

<x-campo name="password" :label="$utilizador ? 'Nova senha (opcional)' : 'Senha'" type="password" :required="! $utilizador" />

<x-campo name="perfil" label="Perfil" type="select" required :value="$utilizador?->perfil">
    <option value="professor" @selected(old('perfil', $utilizador?->perfil) === 'professor')>Professor</option>
    @if (auth()->user()->isSuperAdmin())
        <option value="direcao" @selected(old('perfil', $utilizador?->perfil) === 'direcao')>Direção</option>
        <option value="super_admin" @selected(old('perfil', $utilizador?->perfil) === 'super_admin')>Super Admin</option>
    @endif
</x-campo>

<label class="flex items-center gap-2 text-sm text-marinho-700 dark:text-azul-claro-200">
    <input type="hidden" name="ativo" value="0">
    <input type="checkbox" name="ativo" value="1" class="rounded border-marinho-700/30 text-azul-500 focus:ring-azul-500/40" @checked(old('ativo', $utilizador?->ativo ?? true))>
    Conta ativa
</label>
