<?php

namespace App\Http\Requests\Ocorrencia;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOcorrenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('ocorrencia'));
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'date', 'before_or_equal:today'],
            'tipo' => ['required', 'in:comportamento,academico,disciplinar,elogio'],
            'descricao' => ['required', 'string', 'max:2000'],
            'gravidade' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.required' => 'Indique a data da ocorrência.',
            'data.before_or_equal' => 'A data não pode ser no futuro.',
            'tipo.required' => 'Escolha o tipo de ocorrência.',
            'descricao.required' => 'Descreva a ocorrência.',
            'gravidade.required' => 'Escolha a gravidade (1 a 5 estrelas).',
            'gravidade.between' => 'A gravidade deve estar entre 1 e 5.',
        ];
    }
}
