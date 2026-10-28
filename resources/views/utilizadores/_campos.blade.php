@php $utilizador = $utilizador ?? null; @endphp

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
