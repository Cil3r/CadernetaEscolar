<?php

namespace App\Http\Requests\Falta;

use App\Models\Aluno;
use App\Models\Falta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreFaltaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Falta::class);
    }

    public function rules(): array
    {
        return [
            'aluno_id' => ['required', Rule::exists('alunos', 'id')],
            'data' => ['required', 'date', 'before_or_equal:today'],
            'justificada' => ['boolean'],
            'motivo' => ['nullable', 'string', 'max:255', 'required_if:justificada,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'Escolha o aluno.',
            'data.required' => 'Indique a data da falta.',
            'data.before_or_equal' => 'A data não pode ser no futuro.',
            'motivo.required_if' => 'Indique o motivo da justificação.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->user()->isDirecao() || ! $this->filled('aluno_id')) {
                return;
            }

            $aluno = Aluno::find($this->input('aluno_id'));

            if ($aluno && ! $this->user()->turmas()->whereKey($aluno->turma_id)->exists()) {
                $validator->errors()->add('aluno_id', 'Só pode registar faltas de alunos das suas turmas.');
            }
        });
    }
}
