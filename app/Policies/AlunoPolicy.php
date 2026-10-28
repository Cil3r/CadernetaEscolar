<?php

namespace App\Policies;

use App\Models\Aluno;
use App\Models\User;

class AlunoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Aluno $aluno): bool
    {
        return $this->pertenceAsTurmasDoProfessor($user, $aluno);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Aluno $aluno): bool
    {
        return $this->pertenceAsTurmasDoProfessor($user, $aluno);
    }

    public function delete(User $user, Aluno $aluno): bool
    {
        return $user->isDirecao();
    }

    private function pertenceAsTurmasDoProfessor(User $user, Aluno $aluno): bool
    {
        if ($user->isDirecao()) {
            return true;
        }

        return $user->turmas()->whereKey($aluno->turma_id)->exists();
    }
}
