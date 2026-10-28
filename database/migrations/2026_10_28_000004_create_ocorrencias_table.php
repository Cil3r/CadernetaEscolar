<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->date('data');
            $table->string('tipo', 30)->default('comportamento');
            $table->text('descricao');
            $table->unsignedTinyInteger('gravidade')->default(1);
            $table->timestamps();

            $table->index(['aluno_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
};
