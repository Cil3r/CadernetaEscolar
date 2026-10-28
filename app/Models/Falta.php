<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Falta extends Model
{
    use HasFactory;

    protected $fillable = ['aluno_id', 'user_id', 'data', 'justificada', 'motivo'];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'justificada' => 'boolean',
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
