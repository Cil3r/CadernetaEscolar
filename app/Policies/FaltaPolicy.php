<?php

namespace App\Policies;

use App\Models\Falta;
use App\Models\User;

class FaltaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Falta $falta): bool
    {
        return $this->lecionaATurmaDoAluno($user, $falta);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Falta $falta): bool
    {
        if ($user->isDirecao()) {
            return true;
        }

        return $falta->user_id === $user->id && $this->lecionaATurmaDoAluno($user, $falta);
    }

    public function delete(User $user, Falta $falta): bool
    {
        return $this->update($user, $falta);
    }

    private function lecionaATurmaDoAluno(User $user, Falta $falta): bool
    {
        if ($user->isDirecao()) {
            return true;
        }

        return $user->turmas()->whereKey($falta->aluno->turma_id)->exists();
    }
}
