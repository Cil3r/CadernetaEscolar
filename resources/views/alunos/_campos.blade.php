@php $aluno = $aluno ?? null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-campo name="numero_processo" label="Número de processo" required :value="$aluno?->numero_processo" placeholder="P0001" />
    <x-campo name="nome" label="Nome completo" required :value="$aluno?->nome" />

    <x-campo name="data_nascimento" label="Data de nascimento" type="date" :value="$aluno?->data_nascimento?->format('Y-m-d')" />

    <x-campo name="genero" label="Género" type="select" :value="$aluno?->genero">
        <option value="">—</option>
        <option value="F" @selected(old('genero', $aluno?->genero) === 'F')>Feminino</option>
        <option value="M" @selected(old('genero', $aluno?->genero) === 'M')>Masculino</option>
    </x-campo>

    <x-campo name="turma_id" label="Turma" type="select" required :value="$aluno?->turma_id">
        <option value="">Escolha a turma</option>
        @foreach ($turmasDisponiveis as $turma)
            <option value="{{ $turma->id }}" @selected((string) old('turma_id', $aluno?->turma_id) === (string) $turma->id)>{{ $turma->nome }}</option>
        @endforeach
    </x-campo>

    <x-campo name="encarregado_nome" label="Nome do encarregado" :value="$aluno?->encarregado_nome" />
    <x-campo name="encarregado_contacto" label="Contacto do encarregado" :value="$aluno?->encarregado_contacto" placeholder="923000000" />
</div>

<label class="flex items-center gap-2 text-sm text-marinho-700 dark:text-azul-claro-200">
    <input type="hidden" name="ativo" value="0">
    <input type="checkbox" name="ativo" value="1" class="rounded border-marinho-700/30 text-azul-500 focus:ring-azul-500/40" @checked(old('ativo', $aluno?->ativo ?? true))>
    Aluno ativo
</label>
