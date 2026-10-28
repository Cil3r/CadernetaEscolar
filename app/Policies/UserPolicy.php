<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDirecao();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isDirecao();
    }

    public function create(User $user): bool
    {
        return $user->isDirecao();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isDirecao();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isDirecao() && $user->isNot($model);
    }
}
