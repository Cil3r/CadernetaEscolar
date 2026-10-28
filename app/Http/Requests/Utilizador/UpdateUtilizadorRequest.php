<?php

namespace App\Http\Requests\Utilizador;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUtilizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('utilizador'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('utilizador'))],
            'password' => ['nullable', 'string', 'min:8'],
            'perfil' => ['required', 'in:direcao,professor'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Indique o nome completo.',
            'email.required' => 'Indique o email.',
            'email.unique' => 'Já existe um utilizador com este email.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'perfil.required' => 'Escolha o perfil do utilizador.',
        ];
    }
}
