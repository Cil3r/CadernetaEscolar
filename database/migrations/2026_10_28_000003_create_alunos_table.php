<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo', 30)->unique();
            $table->string('nome');
            $table->date('data_nascimento')->nullable();
            $table->string('genero', 1)->nullable();
            $table->string('encarregado_nome')->nullable();
            $table->string('encarregado_contacto', 30)->nullable();
            $table->foreignId('turma_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
