<?php

namespace App\Policies;

use App\Models\Turma;
use App\Models\User;

class TurmaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Turma $turma): bool
    {
        if ($user->isDirecao()) {
            return true;
        }

        return $turma->professores()->whereKey($user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isDirecao();
    }

    public function update(User $user, Turma $turma): bool
    {
        return $user->isDirecao();
    }

    public function delete(User $user, Turma $turma): bool
    {
        return $user->isDirecao();
    }
}
