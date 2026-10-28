<?php

namespace App\Http\Requests\Turma;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurmaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Turma::class);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'classe' => ['required', 'string', 'max:30'],
            'curso' => ['nullable', 'string', 'max:255'],
            'ano_letivo' => ['required', 'string', 'max:9', 'regex:/^\d{4}\/\d{4}$/'],
            'periodo' => ['required', 'in:manha,tarde,noite'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Indique o nome da turma.',
            'classe.required' => 'Indique a classe.',
            'ano_letivo.required' => 'Indique o ano letivo.',
            'ano_letivo.regex' => 'O ano letivo deve seguir o formato AAAA/AAAA, por exemplo 2026/2027.',
            'periodo.required' => 'Escolha o período.',
            'periodo.in' => 'O período escolhido é inválido.',
        ];
    }
}
