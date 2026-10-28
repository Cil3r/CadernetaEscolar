<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGestor();
    }

    public function view(User $user, User $model): bool
    {
        return $this->podeGerir($user, $model);
    }

    public function create(User $user): bool
    {
        return $user->isGestor();
    }

    public function update(User $user, User $model): bool
    {
        return $this->podeGerir($user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isNot($model) && $this->podeGerir($user, $model);
    }

    private function podeGerir(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isDirecao()) {
            return $model->isProfessor();
        }

        return false;
    }
}
