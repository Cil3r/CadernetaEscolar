<?php

namespace App\Policies;

use App\Models\Ocorrencia;
use App\Models\User;

class OcorrenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ocorrencia $ocorrencia): bool
    {
        return $this->lecionaATurmaDoAluno($user, $ocorrencia);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ocorrencia $ocorrencia): bool
    {
        if ($user->isGestor()) {
            return true;
        }

        return $ocorrencia->user_id === $user->id && $this->lecionaATurmaDoAluno($user, $ocorrencia);
    }

    public function delete(User $user, Ocorrencia $ocorrencia): bool
    {
        return $this->update($user, $ocorrencia);
    }

    private function lecionaATurmaDoAluno(User $user, Ocorrencia $ocorrencia): bool
    {
        if ($user->isGestor()) {
            return true;
        }

        return $user->turmas()->whereKey($ocorrencia->aluno->turma_id)->exists();
    }
}
