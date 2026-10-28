<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('classe', 30);
            $table->string('curso')->nullable();
            $table->string('ano_letivo', 9);
            $table->string('periodo', 20)->default('manha');
            $table->timestamps();
        });

        Schema::create('turma_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('disciplina')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turma_professor');
        Schema::dropIfExists('turmas');
    }
};
