<?php

namespace App\Http\Requests\Utilizador;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUtilizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        $perfisPermitidos = $this->user()->isSuperAdmin()
            ? ['super_admin', 'direcao', 'professor']
            : ['professor'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'perfil' => ['required', Rule::in($perfisPermitidos)],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Indique o nome completo.',
            'email.required' => 'Indique o email.',
            'email.unique' => 'Já existe um utilizador com este email.',
            'password.required' => 'Defina uma senha.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'perfil.required' => 'Escolha o perfil do utilizador.',
        ];
    }
}
