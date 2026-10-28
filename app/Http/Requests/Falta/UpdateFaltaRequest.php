<?php

namespace App\Http\Requests\Falta;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaltaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('falta'));
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'date', 'before_or_equal:today'],
            'justificada' => ['boolean'],
            'motivo' => ['nullable', 'string', 'max:255', 'required_if:justificada,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.required' => 'Indique a data da falta.',
            'data.before_or_equal' => 'A data não pode ser no futuro.',
            'motivo.required_if' => 'Indique o motivo da justificação.',
        ];
    }
}
