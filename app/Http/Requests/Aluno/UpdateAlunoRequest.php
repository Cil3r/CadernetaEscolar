<?php

namespace App\Http\Requests\Aluno;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('aluno'));
    }

    public function rules(): array
    {
        $regrasTurma = ['required', Rule::exists('turmas', 'id')];

        if (! $this->user()->isDirecao()) {
            $regrasTurma[] = Rule::in($this->user()->turmas()->pluck('turmas.id'));
        }

        return [
            'numero_processo' => ['required', 'string', 'max:30', Rule::unique('alunos', 'numero_processo')->ignore($this->route('aluno'))],
            'nome' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
            'genero' => ['nullable', 'in:F,M'],
            'encarregado_nome' => ['nullable', 'string', 'max:255'],
            'encarregado_contacto' => ['nullable', 'string', 'max:30'],
            'turma_id' => $regrasTurma,
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_processo.required' => 'Indique o número de processo.',
            'numero_processo.unique' => 'Já existe um aluno com este número de processo.',
            'nome.required' => 'Indique o nome do aluno.',
            'turma_id.required' => 'Escolha a turma do aluno.',
            'turma_id.in' => 'Só pode atribuir alunos às turmas que lecciona.',
        ];
    }
}
