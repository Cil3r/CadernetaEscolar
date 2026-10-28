@php $turma = $turma ?? null; @endphp

<x-campo name="nome" label="Nome da turma" required :value="$turma?->nome" placeholder="10.ª A" />

<x-campo name="classe" label="Classe" required :value="$turma?->classe" placeholder="10.ª Classe" />

<x-campo name="curso" label="Curso" :value="$turma?->curso" placeholder="Informática" />

<x-campo name="ano_letivo" label="Ano letivo" required :value="$turma?->ano_letivo" placeholder="2026/2027" />

<x-campo name="periodo" label="Período" type="select" required :value="$turma?->periodo">
    <option value="manha" @selected(old('periodo', $turma?->periodo) === 'manha')>Manhã</option>
    <option value="tarde" @selected(old('periodo', $turma?->periodo) === 'tarde')>Tarde</option>
    <option value="noite" @selected(old('periodo', $turma?->periodo) === 'noite')>Noite</option>
</x-campo>
