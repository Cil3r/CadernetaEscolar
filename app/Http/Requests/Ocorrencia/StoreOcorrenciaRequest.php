<?php

namespace App\Http\Requests\Ocorrencia;

use App\Models\Aluno;
use App\Models\Ocorrencia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreOcorrenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Ocorrencia::class);
    }

    public function rules(): array
    {
        return [
            'aluno_id' => ['required', Rule::exists('alunos', 'id')],
            'data' => ['required', 'date', 'before_or_equal:today'],
            'tipo' => ['required', 'in:comportamento,academico,disciplinar,elogio'],
            'descricao' => ['required', 'string', 'max:2000'],
            'gravidade' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'Escolha o aluno.',
            'data.required' => 'Indique a data da ocorrência.',
            'data.before_or_equal' => 'A data não pode ser no futuro.',
            'tipo.required' => 'Escolha o tipo de ocorrência.',
            'descricao.required' => 'Descreva a ocorrência.',
            'gravidade.required' => 'Escolha a gravidade (1 a 5 estrelas).',
            'gravidade.between' => 'A gravidade deve estar entre 1 e 5.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->user()->isGestor() || ! $this->filled('aluno_id')) {
                return;
            }

            $aluno = Aluno::find($this->input('aluno_id'));

            if ($aluno && ! $this->user()->turmas()->whereKey($aluno->turma_id)->exists()) {
                $validator->errors()->add('aluno_id', 'Só pode registar ocorrências de alunos das suas turmas.');
            }
        });
    }
}
