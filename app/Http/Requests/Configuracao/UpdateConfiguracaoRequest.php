<?php

namespace App\Http\Requests\Configuracao;

use App\Models\Configuracao;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfiguracaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Configuracao::class);
    }

    public function rules(): array
    {
        return [
            'limite_faltas' => ['required', 'integer', 'min:1', 'max:365'],
            'limite_gravidade' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'limite_faltas.required' => 'Indique o limite de faltas injustificadas.',
            'limite_faltas.min' => 'O limite de faltas deve ser pelo menos 1.',
            'limite_gravidade.required' => 'Indique o limiar de gravidade.',
            'limite_gravidade.min' => 'O limiar de gravidade deve estar entre 1 e 5.',
            'limite_gravidade.max' => 'O limiar de gravidade deve estar entre 1 e 5.',
        ];
    }
}
