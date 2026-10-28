<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor'];

    public static function obter(string $chave, $padrao = null)
    {
        return static::where('chave', $chave)->value('valor') ?? $padrao;
    }
}
