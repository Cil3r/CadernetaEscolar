<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turma extends Model
{
    protected $fillable = ['nome', 'classe', 'curso', 'ano_letivo', 'periodo'];

    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }

    public function professores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'turma_professor')
            ->withPivot('disciplina')
            ->withTimestamps();
    }
}
