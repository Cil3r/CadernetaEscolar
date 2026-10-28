<?php

namespace App\Policies;

use App\Models\Configuracao;
use App\Models\User;

class ConfiguracaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGestor();
    }

    public function update(User $user, Configuracao $configuracao): bool
    {
        return $user->isGestor();
    }
}
