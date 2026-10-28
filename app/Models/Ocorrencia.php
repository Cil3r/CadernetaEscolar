<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ocorrencia extends Model
{
    protected $table = 'ocorrencias';

    protected $fillable = ['aluno_id', 'user_id', 'data', 'tipo', 'descricao', 'gravidade'];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'gravidade' => 'integer',
        ];
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
